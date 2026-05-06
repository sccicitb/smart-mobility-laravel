<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


#[Title('Pengujian Kendaraan Otonom')]
class KendaraanOtonom extends Component
{
    public string $filter = 'today';
    public bool $isLoading = false;

    // Readiness matrix per simpang
    public array $readinessData = [];

    // V/C Ratio dari tabel kapasitas
    public array $vcData = [];

    // Obstacle density (KTB) per simpang
    public array $obstacleData = [];

    // Virtual Test Drive log (jarak_simpang updated terbaru)
    public array $testLog = [];

    // Summary
    public float $avgHeterogenitas = 0;
    public float $avgVc            = 0;
    public float $avgObstacle      = 0;
    public int   $readyCount       = 0;

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
        Cache::forget("av_{$filter}_readiness");
        Cache::forget("av_{$filter}_vc");
        Cache::forget("av_{$filter}_obstacle");
        
        // Load data fresh
        $this->loadAll();
        
        // Dispatch event for chart update
        $this->dispatch('filterChanged', $this->filter);
        
        // Dispatch event to update charts
        $this->dispatch('av-chart-update', [
            'readiness' => $this->readinessData,
            'vc' => $this->vcData,
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

        // ── Heterogenitas + Obstacle per simpang ──
        $arus = Cache::remember("av_{$filter}_readiness", now()->addMinutes(10), function () use ($start, $end) {
            return DB::table('arus as a')
                ->leftJoin('simpang as s', 'a.ID_Simpang', '=', 's.id')
                ->selectRaw('
                    a.ID_Simpang,
                    s.Nama_Simpang,
                    SUM(a.SM) as total_sm,
                    SUM(a.KTB) as total_ktb,
                    SUM(a.SM + a.MP + a.AUP + a.TR + a.BS + a.TS + a.TB + a.BB + a.GANDENG + a.KTB) as grand_total,
                    SUM(a.MP + a.AUP + a.TR + a.BS + a.TS + a.TB + a.BB + a.GANDENG) as total_besar
                ')
                ->whereBetween(DB::raw('DATE(a.waktu)'), [$start, $end])
                ->groupBy('a.ID_Simpang', 's.Nama_Simpang')
                ->get();
        });

        // ── V/C Ratio dari kapasitas ──
        $vc = Cache::remember("av_{$filter}_vc", now()->addMinutes(10), function () {
            return DB::table('kapasitas as k')
                ->leftJoin('simpang as s', 'k.ID_Simpang', '=', 's.id')
                ->selectRaw('k.ID_Simpang, s.Nama_Simpang,
                             AVG(k.Derajat_Kejenuhan) as avg_vc,
                             SUM(k.Q) as total_q,
                             SUM(k.Kapasitas) as total_kapasitas')
                ->groupBy('k.ID_Simpang', 's.Nama_Simpang')
                ->get();
        });

        $vcBySimpang = $vc->keyBy('ID_Simpang');

        $this->readinessData = [];
        $totalHet = 0; $totalVc = 0; $totalObs = 0; $n = 0;

        foreach ($arus as $row) {
            $grandTotal = max((int) $row->grand_total, 1);
            $smRatio    = round((int) $row->total_sm / $grandTotal * 100, 1);
            $ktbRatio   = round((int) $row->total_ktb / $grandTotal * 100, 1);

            $vcRow      = $vcBySimpang[$row->ID_Simpang] ?? null;
            $vcRatio    = $vcRow ? round((float) $vcRow->avg_vc, 3) : null;

            // Score 0–100: lebih tinggi = lebih siap
            $smScore  = max(0, 100 - $smRatio);          // SM rendah = baik
            $obsScore = max(0, 100 - ($ktbRatio * 5));   // KTB rendah = baik (amplified)
            $vcScore  = $vcRatio !== null
                ? max(0, round((1 - $vcRatio) * 100))
                : 50;

            $score = round(($smScore * 0.4) + ($vcScore * 0.4) + ($obsScore * 0.2));

            $this->readinessData[] = [
                'id'        => $row->ID_Simpang,
                'nama'      => $row->Nama_Simpang ?? "Simpang #{$row->ID_Simpang}",
                'sm_ratio'  => $smRatio,
                'ktb_ratio' => $ktbRatio,
                'vc_ratio'  => $vcRatio,
                'score'     => $score,
                'status'    => $score >= 70 ? 'ready' : ($score >= 45 ? 'conditional' : 'not-ready'),
                'volume'    => (int) $row->grand_total,
            ];
            $totalHet += $smRatio;
            $totalVc  += ($vcRatio ?? 0);
            $totalObs += $ktbRatio;
            $n++;
        }

        if ($n > 0) {
            $this->avgHeterogenitas = round($totalHet / $n, 1);
            $this->avgVc            = round($totalVc  / $n, 3);
            $this->avgObstacle      = round($totalObs / $n, 2);
        }
        $this->readyCount = count(array_filter($this->readinessData, fn($r) => $r['status'] === 'ready'));

        // Data untuk chart V/C
        usort($this->readinessData, fn($a, $b) => $b['score'] <=> $a['score']);
        $this->vcData = [
            'labels' => array_column($this->readinessData, 'nama'),
            'vc'     => array_map(fn($r) => $r['vc_ratio'] ?? 0, $this->readinessData),
            'sm'     => array_column($this->readinessData, 'sm_ratio'),
            'ktb'    => array_column($this->readinessData, 'ktb_ratio'),
            'score'  => array_column($this->readinessData, 'score'),
        ];

        // ── Virtual Test Drive Log (jarak_simpang terbaru) ──
        $this->testLog = Cache::remember("av_testlog", now()->addMinutes(5), function () {
            return DB::table('jarak_simpang as j')
                ->leftJoin('simpang as s', 'j.ID_Simpang', '=', 's.id')
                ->select('j.id', 's.Nama_Simpang', 'j.nama_ruas', 'j.dari_arah',
                         'j.ke_arah', 'j.jarak_km', 'j.lebar_jalan', 'j.status', 'j.updated_at')
                ->orderByDesc('j.updated_at')
                ->limit(10)
                ->get()
                ->toArray();
        });
    }

    public function render()
    {
        return view('livewire.kendaraan-otonom');
    }
}
