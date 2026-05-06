<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PerbandinganKendaraan extends Component
{
    public $jenisKendaraan = [];
    public $selectedFilter = 'hari'; // Default filter
    public $dataArah = [];

    protected $listeners = ['updateFilter'];

    public function mount()
    {
        $this->fetchData();
        $this->fetchDistribusiData();
        
    }

    public function updateFilter($filter)
    {
        $this->selectedFilter = $filter;
        $this->fetchData(); // Update data setelah filter berubah
        $this->fetchDistribusiData();
    }

    public function fetchData()
    {
        $periode = $this->selectedFilter;
        $data = DB::select("CALL get_kendaraan_statistik(?)", [$periode]);
        // $data = DB::table('kendaraans')->get();
        $this->jenisKendaraan = collect($data)->map(function ($item) {
            return [
                'name' => $this->mapJenisKendaraan($item->jenis),
                'masuk' => -$item->total_masuk,  // Masuk dibuat negatif untuk grafik
                'keluar' => $item->total_keluar,
            ];
        })->toArray();

        // 🔥 Gunakan dispatch() agar Alpine.js bisa menangkap update dari Livewire
        $this->dispatch('updateChartData', detail: $this->jenisKendaraan);
    }

    public function fetchDistribusiData()
    {
        $periode = $this->selectedFilter;
    
        // Mapping ID ke arah dan lokasi
        $idArahMapping = [
            2 => 'Timur',
            3 => 'Barat',
            4 => 'Selatan',
            5 => 'Utara',
        ];
    
        $lokasiMapping = [
            'Utara' => 'Tempel',
            'Timur' => 'Prambanan',
            'Selatan' => 'Piyungan',
            'Barat' => 'Glagah'
        ];
    
        $colorMapping = [
            'SM' => '#F39C12',
            'MP' => '#2ECC71',
            'AUP' => '#3498DB',
            'TR' => '#34495E',
            'BS' => '#E74C3C',
            'TS' => '#9B59B6',
            'BB' => '#1ABC9C',
            'TB' => '#D35400',
            'Gandeng' => '#8E44AD',
            'KTB' => '#8E44AD'
        ];
    
        $dataArah = [];
    
        foreach ($idArahMapping as $id => $arah) {
            $result = collect(DB::select("CALL get_kendaraan_breakdown(?, ?)", [$periode, $id]));
    
            $totalKendaraan = $result->sum('total_kendaraan');
    
            $jenisData = [];
            foreach ($colorMapping as $kode => $color) {
                $item = $result->firstWhere('jenis_kendaraan', $kode);
                $jumlah = $item->total_kendaraan ?? 0;
    
                $jenisData[] = [
                    'name' => $kode,
                    'masuk' => $jumlah,
                    'keluar' => $jumlah,
                    'color' => $color
                ];
            }
    
            $dataArah[] = [
                'arah' => $arah,
                'lokasi' => $lokasiMapping[$arah],
                'totalMasuk' => $totalKendaraan,
                'totalKeluar' => $totalKendaraan,
                'jenis' => $jenisData
            ];
        }
    
        $this->dataArah = $dataArah;
    
        // 🔥 Kirim event ke Alpine untuk update chart
        $this->dispatch('updateChartDistribusi', detail: $dataArah);
    }
    
    public function render()
    {
        return view('livewire.perbandingan-kendaraan');
    }

    private function mapJenisKendaraan($kode)
    {
        $mapping = [
            'SM' => 'Sepeda Motor',
            'MP' => 'Mobil Pribadi',
            'AUP' => 'Angkutan Umum Penumpang',
            'TR' => 'Truk Ringan',
            'BS' => 'Bus Sedang',
            'TS' => 'Bus Sedang',
            'BB' => 'Bus Besar',
            'TB' => 'Truk Berat',
            'Gandeng' => 'Gandeng',
            'KTB' => 'Kendaraan Tak Bermotor'
        ];

        return $mapping[$kode] ?? $kode;
    }
}
