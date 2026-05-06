<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TransportasiPublik extends Component
{
    public string $filter = 'today';
    public bool $isLoading = false;

    // Summary stats kendaraan publik
    public int   $totalBs        = 0;
    public int   $totalAup       = 0;
    public int   $totalBb        = 0;
    public int   $totalPublik    = 0;
    public int   $totalKendaraan = 0;
    public float $loadFactor     = 0;

    // Chart: publik per simpang
    public array $simpangData  = [];

    // Chart: publik per pendekat
    public array $pendekatData = [];

    protected $listeners = ['chart-loading' => 'setLoading'];

    public function setLoading(bool $status): void
    {
        $this->isLoading = $status;
    }

    public function mount(): void
    {
        $this->loadAll();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        
        // Clear cache for the filter
        Cache::forget("tp_{$filter}_stats");
        Cache::forget("tp_{$filter}_pendekat");
        Cache::forget("tp_{$filter}_simpang");
        
        // Load data fresh
        $this->loadAll();
        
        // Dispatch event for chart update
        $this->dispatch('filterChanged', $this->filter);
        
        // Dispatch event to update charts
        $this->dispatch('tp-chart-update', [
            'simpang' => $this->simpangData,
            'pendekat' => $this->pendekatData,
        ]);
    }

    private function dateRange(): array
    {
        return match ($this->filter) {
            'week'  => [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')],
            'month' => [now()->startOfMonth()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')],
            default => [today()->format('Y-m-d'), today()->format('Y-m-d')],
        };
    }

    public function loadAll(): void
    {
        [$start, $end] = $this->dateRange();
        $filter = $this->filter;

        // ── Stats ──
        $stats = Cache::remember("tp_{$filter}_stats", now()->addMinutes(10), function () use ($start, $end) {
            return DB::table('arus')
                ->selectRaw('SUM(BS) as bs, SUM(AUP) as aup, SUM(BB) as bb,
                             SUM(SM+MP+AUP+TR+BS+TS+TB+BB+GANDENG+KTB) as total')
                ->whereBetween(DB::raw('DATE(waktu)'), [$start, $end])
                ->first();
        });

        $this->totalBs        = (int) ($stats->bs    ?? 0);
        $this->totalAup       = (int) ($stats->aup   ?? 0);
        $this->totalBb        = (int) ($stats->bb    ?? 0);
        $this->totalPublik    = $this->totalBs + $this->totalAup + $this->totalBb;
        $this->totalKendaraan = (int) ($stats->total ?? 0);
        $this->loadFactor     = $this->totalKendaraan > 0
            ? round($this->totalPublik / $this->totalKendaraan * 100, 1)
            : 0;

        // ── Chart: publik per simpang ──
        $simpang = Cache::remember("tp_{$filter}_simpang", now()->addMinutes(10), function () use ($start, $end) {
            return DB::table('arus as a')
                ->leftJoin('simpang as s', 'a.ID_Simpang', '=', 's.id')
                ->selectRaw('s.nama_simpang, a.ID_Simpang,
                             SUM(a.BS) as bs, SUM(a.AUP) as aup, SUM(a.BB) as bb')
                ->whereBetween(DB::raw('DATE(a.waktu)'), [$start, $end])
                ->groupBy('a.ID_Simpang', 's.nama_simpang')
                ->orderByDesc(DB::raw('SUM(a.BS+a.AUP+a.BB)'))
                ->get();
        });

        $this->simpangData = [
            'labels' => $simpang->map(fn($r) => $r->nama_simpang ?? "Simpang #{$r->ID_Simpang}")->toArray(),
            'bs'     => $simpang->pluck('bs')->map(fn($v) => (int) $v)->toArray(),
            'aup'    => $simpang->pluck('aup')->map(fn($v) => (int) $v)->toArray(),
            'bb'     => $simpang->pluck('bb')->map(fn($v) => (int) $v)->toArray(),
        ];

        // ── Chart: publik per pendekat ──
        $pendekat = Cache::remember("tp_{$filter}_pendekat", now()->addMinutes(10), function () use ($start, $end) {
            return DB::table('arus')
                ->selectRaw('tipe_pendekat, SUM(BS) as bs, SUM(AUP) as aup, SUM(BB) as bb')
                ->whereBetween(DB::raw('DATE(waktu)'), [$start, $end])
                ->whereNotNull('tipe_pendekat')
                ->groupBy('tipe_pendekat')
                ->orderByDesc(DB::raw('SUM(BS+AUP+BB)'))
                ->limit(10)
                ->get();
        });

        $this->pendekatData = [
            'labels' => $pendekat->pluck('tipe_pendekat')->toArray(),
            'bs'     => $pendekat->pluck('bs')->map(fn($v) => (int) $v)->toArray(),
            'aup'    => $pendekat->pluck('aup')->map(fn($v) => (int) $v)->toArray(),
            'bb'     => $pendekat->pluck('bb')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.transportasi-publik')
            ->title('Integrasi Transportasi Publik');
    }
}
