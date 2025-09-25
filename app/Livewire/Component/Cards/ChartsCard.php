<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ChartsCard extends Component
{
    public $filter;
    public $chartType = 'bar';
    public $title = 'Chart Title';
    public $parentFilter;
    public $selectedDate; // Tanggal yang dipilih
    public $selectedSimpang; // ID Simpang yang dipilih
    public $data = [
        'kendaraan_ringan' => [],
        'kendaraan_berat' => [],
        'total_kendaraan' => []
    ];
    public $labels = [];
    public $chartId;

    protected $listeners = [
        'filterChanged' => 'updateFromParent',
        'emisiData' => 'updateEmisi' // 🔹 tambahan
    ];

    public $emisi = [];

    public function updateEmisi($payload)
    {
        $this->emisi = $payload;

        logger()->info('Emisi data diterima di chart', $payload);

        // kalau mau langsung trigger update chart:
        $eventData = [
            'chartId' => $this->chartId,
            'chartType' => $this->chartType,
            'labels' => $this->labels,
            'data' => $this->data,
            'emisi' => $this->emisi // 🔹 ikut disertakan
        ];

        $this->dispatch('chartDataUpdated', $eventData);

        $this->js("
            const eventData = " . json_encode($eventData) . ";
            window.dispatchEvent(new CustomEvent('chartDataUpdated', { detail: eventData }));
        ");
    }

    public function mount($chartType = 'bar', $title = 'Analisis Arus Lalu Lintas', $filter = 'today', $selectedDate = null, $selectedSimpang = null)
    {
        $this->chartType = $chartType;
        $this->title = $title;
        $this->parentFilter = $filter;
        $this->selectedDate = $selectedDate ?: Carbon::today()->format('Y-m-d');
        $this->selectedSimpang = $selectedSimpang;
        $this->chartId = 'chart-' . uniqid();
        $this->loadChartData();
    }

    public function changeChartType($type)
    {
        \Log::debug("Changing chart type to: {$type}");

        if (!in_array($type, ['line', 'bar', 'donut'])) {
            $type = 'line';
        }

        $this->chartType = $type;
        $this->loadChartData();

        $eventData = [
            'chartId' => $this->chartId,
            'chartType' => $this->chartType,
            'labels' => $this->labels,
            'data' => $this->data
        ];

        $this->dispatch('chartDataUpdated', $eventData);

        $this->js("
            const eventData = " . json_encode($eventData) . ";
            window.dispatchEvent(new CustomEvent('chartDataUpdated', {
                detail: eventData
            }));
        ");
    }

    public function setFilter($newFilter)
    {
        $this->filter = $newFilter;
    }

    public function updateFromParent($newFilter, $selectedDate = null, $selectedSimpang = null)
    {
        $this->parentFilter = $newFilter;
        if ($selectedDate)
            $this->selectedDate = $selectedDate;
        if ($selectedSimpang)
            $this->selectedSimpang = $selectedSimpang;

        $this->loadChartData();

        logger()->info('FilterChanged diterima', [
            'filter' => $newFilter,
            'date' => $this->selectedDate,
            'simpang' => $this->selectedSimpang
        ]);

        $eventData = [
            'chartId' => $this->chartId,
            'chartType' => $this->chartType,
            'labels' => $this->labels,
            'data' => $this->data
        ];

        $this->dispatch('chartDataUpdated', $eventData);

        $this->js("
            const eventData = " . json_encode($eventData) . ";
            window.dispatchEvent(new CustomEvent('chartDataUpdated', { detail: eventData }));
        ");
    }

    public function loadChartData()
    {
        switch ($this->parentFilter) {
            case 'today':
                $this->loadHourlyData();
                break;
            case '15min':
                $this->load15MinuteData();
                break;
            case 'week':
                $this->loadWeeklyData();
                break;
            case 'month':
                $this->loadMonthlyData();
                break;
            default:
                $this->loadHourlyData();
        }

        \Log::debug("Chart data loaded for filter {$this->parentFilter}: ", [
            'labels' => $this->labels,
            'data' => $this->data
        ]);
    }

    private function loadHourlyData()
    {
        $cacheKey = "chart_hourly_{$this->selectedDate}_{$this->selectedSimpang}";

        $result = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $hoursRange = range(0, 23);
            $labels = array_map(fn($h) => sprintf('%02d:00', $h), $hoursRange);

            $query = "
            SELECT 
                HOUR(waktu) as jam,
                SUM(SM + MP + AUP) as kendaraan_ringan,
                SUM(TR + BS + TS + TB + BB + GANDENG) as kendaraan_berat,
                SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) as total_kendaraan
            FROM arus
            WHERE DATE(waktu) = ?
        ";

            $params = [$this->selectedDate];

            if ($this->selectedSimpang) {
                $query .= " AND ID_Simpang = ?";
                $params[] = $this->selectedSimpang;
            }

            $query .= " GROUP BY HOUR(waktu) ORDER BY jam";

            $dbData = DB::select($query, $params);

            $dataByHour = [];
            foreach ($dbData as $row) {
                $dataByHour[$row->jam] = $row;
            }

            $kendaraanRingan = [];
            $kendaraanBerat = [];
            $totalKendaraan = [];

            foreach ($hoursRange as $hour) {
                if (isset($dataByHour[$hour])) {
                    $kendaraanRingan[] = (int) $dataByHour[$hour]->kendaraan_ringan;
                    $kendaraanBerat[] = (int) $dataByHour[$hour]->kendaraan_berat;
                    $totalKendaraan[] = (int) $dataByHour[$hour]->total_kendaraan;
                } else {
                    $kendaraanRingan[] = 0;
                    $kendaraanBerat[] = 0;
                    $totalKendaraan[] = 0;
                }
            }

            return [
                'labels' => $labels,
                'data' => [
                    'kendaraan_ringan' => $kendaraanRingan,
                    'kendaraan_berat' => $kendaraanBerat,
                    'total_kendaraan' => $totalKendaraan
                ]
            ];
        });

        $this->labels = $result['labels'];
        $this->data = $result['data'];
    }

    private function load15MinuteData()
    {
        // Buat array 96 interval 15 menit (24 jam x 4)
        $intervals = [];
        for ($h = 0; $h < 24; $h++) {
            for ($m = 0; $m < 60; $m += 15) {
                $intervals[] = ['hour' => $h, 'minute' => $m];
            }
        }

        $this->labels = array_map(fn($i) => sprintf('%02d:%02d', $i['hour'], $i['minute']), $intervals);

        // Query data per 15 menit
        $query = "
            SELECT 
                HOUR(waktu) as jam,
                FLOOR(MINUTE(waktu) / 15) * 15 as menit_interval,
                SUM(SM + MP + AUP) as kendaraan_ringan,
                SUM(TR + BS + TS + TB + BB + GANDENG) as kendaraan_berat,
                SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) as total_kendaraan
            FROM arus
            WHERE DATE(waktu) = ?
        ";

        $params = [$this->selectedDate];

        if ($this->selectedSimpang) {
            $query .= " AND ID_Simpang = ?";
            $params[] = $this->selectedSimpang;
        }

        $query .= " GROUP BY HOUR(waktu), FLOOR(MINUTE(waktu) / 15) ORDER BY jam, menit_interval";

        $dbData = DB::select($query, $params);

        // Convert ke array dengan key jam_menit
        $dataByInterval = [];
        foreach ($dbData as $row) {
            $key = $row->jam . '_' . $row->menit_interval;
            $dataByInterval[$key] = $row;
        }

        // Fill data untuk semua interval
        $kendaraanRingan = [];
        $kendaraanBerat = [];
        $totalKendaraan = [];

        foreach ($intervals as $interval) {
            $key = $interval['hour'] . '_' . $interval['minute'];

            if (isset($dataByInterval[$key])) {
                $kendaraanRingan[] = (int) $dataByInterval[$key]->kendaraan_ringan;
                $kendaraanBerat[] = (int) $dataByInterval[$key]->kendaraan_berat;
                $totalKendaraan[] = (int) $dataByInterval[$key]->total_kendaraan;
            } else {
                $kendaraanRingan[] = 0;
                $kendaraanBerat[] = 0;
                $totalKendaraan[] = 0;
            }
        }

        $this->data = [
            'kendaraan_ringan' => $kendaraanRingan,
            'kendaraan_berat' => $kendaraanBerat,
            'total_kendaraan' => $totalKendaraan
        ];
    }

    private function loadWeeklyData()
    {
        // Data 7 hari terakhir
        $startDate = Carbon::parse($this->selectedDate)->subDays(6);
        $endDate = Carbon::parse($this->selectedDate);

        $this->labels = [];
        $dates = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $this->labels[] = $date->format('d/m');
            $dates[] = $date->format('Y-m-d');
        }

        $query = "
            SELECT 
                DATE(waktu) as tanggal,
                SUM(SM + MP + AUP) as kendaraan_ringan,
                SUM(TR + BS + TS + TB + BB + GANDENG) as kendaraan_berat,
                SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) as total_kendaraan
            FROM arus
            WHERE DATE(waktu) BETWEEN ? AND ?
        ";

        $params = [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')];

        if ($this->selectedSimpang) {
            $query .= " AND ID_Simpang = ?";
            $params[] = $this->selectedSimpang;
        }

        $query .= " GROUP BY DATE(waktu) ORDER BY tanggal";

        $dbData = DB::select($query, $params);

        // Convert ke array dengan key tanggal
        $dataByDate = [];
        foreach ($dbData as $row) {
            $dataByDate[$row->tanggal] = $row;
        }

        // Fill data untuk 7 hari
        $kendaraanRingan = [];
        $kendaraanBerat = [];
        $totalKendaraan = [];

        foreach ($dates as $date) {
            if (isset($dataByDate[$date])) {
                $kendaraanRingan[] = (int) $dataByDate[$date]->kendaraan_ringan;
                $kendaraanBerat[] = (int) $dataByDate[$date]->kendaraan_berat;
                $totalKendaraan[] = (int) $dataByDate[$date]->total_kendaraan;
            } else {
                $kendaraanRingan[] = 0;
                $kendaraanBerat[] = 0;
                $totalKendaraan[] = 0;
            }
        }

        $this->data = [
            'kendaraan_ringan' => $kendaraanRingan,
            'kendaraan_berat' => $kendaraanBerat,
            'total_kendaraan' => $totalKendaraan
        ];
    }

    private function loadMonthlyData()
    {
        // Data per minggu dalam bulan
        $month = Carbon::parse($this->selectedDate);
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $this->labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'];

        $query = "
            SELECT 
                WEEK(waktu, 1) - WEEK(DATE_SUB(waktu, INTERVAL DAYOFMONTH(waktu) - 1 DAY), 1) + 1 as minggu,
                SUM(SM + MP + AUP) as kendaraan_ringan,
                SUM(TR + BS + TS + TB + BB + GANDENG) as kendaraan_berat,
                SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) as total_kendaraan
            FROM arus
            WHERE DATE(waktu) BETWEEN ? AND ?
        ";

        $params = [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')];

        if ($this->selectedSimpang) {
            $query .= " AND ID_Simpang = ?";
            $params[] = $this->selectedSimpang;
        }

        $query .= " GROUP BY minggu ORDER BY minggu";

        $dbData = DB::select($query, $params);

        // Convert ke array dengan key minggu
        $dataByWeek = [];
        foreach ($dbData as $row) {
            $dataByWeek[$row->minggu] = $row;
        }

        // Fill data untuk 5 minggu maksimal
        $kendaraanRingan = [];
        $kendaraanBerat = [];
        $totalKendaraan = [];

        for ($week = 1; $week <= 5; $week++) {
            if (isset($dataByWeek[$week])) {
                $kendaraanRingan[] = (int) $dataByWeek[$week]->kendaraan_ringan;
                $kendaraanBerat[] = (int) $dataByWeek[$week]->kendaraan_berat;
                $totalKendaraan[] = (int) $dataByWeek[$week]->total_kendaraan;
            } else {
                $kendaraanRingan[] = 0;
                $kendaraanBerat[] = 0;
                $totalKendaraan[] = 0;
            }
        }

        $this->data = [
            'kendaraan_ringan' => $kendaraanRingan,
            'kendaraan_berat' => $kendaraanBerat,
            'total_kendaraan' => $totalKendaraan
        ];
    }

    public function render()
    {
        return view('livewire.component.cards.charts-card');
    }
}