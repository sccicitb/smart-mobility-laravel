<?php

namespace App\Livewire;
use Livewire\Component;
// use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Services\VehicleQuery;
class DashboardNew extends Component
{
    protected $middleware = ['auth'];
    public $title = "Dashboard";
    public $titleBar = "Intersection Traffic Flow per Vehicle Type";
    public $descriptionWelcome = "Smart Mobility Simulator is a powerful tool that replicates traffic flow at intersections. It uses real-time traffic data from video analytics, allowing users to instantly manipulate variables like traffic light timing and lane width in a safe virtual environment. It's a virtual 'laboratory' to test scenarios without the risk or cost of physical implementation.";
    public $filter = 'today';
    public $data = [
        'carbon' => 0,
        'energy' => 0,
        'water' => 0,
    ];
    public $data_emisi = [];
    public $isLoading = false;
    public $peakTime = '';
    public $tanggal;
    public $vehicleData = [];

    public $selectedDate = '';

    // public function mount()
    // {
    //     $this->loadData();
    //     $this->loadVehicleData();

    //     if (auth()->check()) {
    //         $user = auth()->user();
    //         \Log::info('User Login Info', ['user' => $user]);
    //     }

    //     $allSession = session()->all();
    //     \Log::info('Session Data', $allSession);

    //     $laravelSessionId = session()->getId();
    //     \Log::info('Laravel Session ID', ['id' => $laravelSessionId]);

    //     // default hari ini
    //     $this->tanggal = '2025-09-11';
    //     $this->fetchData();
    // }

    public $cost = 0;
    public $los = '';
    protected $listeners = ['chart-loading' => 'setLoading'];
    public function setLoading($status)
    {
        $this->isLoading = $status;
    }
    public function fetchData()
    {
        $this->isLoading = true;

        $key = "emisi_{$this->filter}";

        try {

            $this->data_emisi = Cache::remember($key, now()->addMinutes(10), function () {
                $query = DB::table('arus as a')
                    ->leftJoin('jarak_simpang as j', function ($join) {
                        $join->on('a.ID_Simpang', '=', 'j.ID_Simpang')
                            ->on('a.dari_arah', '=', 'j.dari_arah')
                            ->on('a.ke_arah', '=', 'j.ke_arah')
                            ->where('j.status', '=', 'aktif');
                    })
                    ->selectRaw("
                SUM(
                    (
                      (a.SM * 80 * j.jarak_km) + 
                      (a.MP * 180 * j.jarak_km) + 
                      (a.AUP * 350 * j.jarak_km) + 
                      (a.TR * 250 * j.jarak_km) + 
                      (a.BS * 800 * j.jarak_km) + 
                      (a.TS * 400 * j.jarak_km) + 
                      (a.TB * 600 * j.jarak_km) + 
                      (a.BB * 1200 * j.jarak_km) + 
                      (a.GANDENG * 900 * j.jarak_km)
                    ) / NULLIF(j.lebar_jalan,0)
                ) / 1000 AS total_emisi_co2_kg,
    
                SUM(
                    (a.SM * 10 + a.MP * 20 + 
                     (a.AUP + a.TR + a.BS + a.TS + a.TB + a.BB + a.GANDENG) * 40
                    ) * j.jarak_km
                ) AS total_kerugian_ribu_rp
            ")
                    ->whereNotNull('j.jarak_km');

                // Filter waktu
                if ($this->filter === 'today') {
                    $query->whereDate('a.waktu', today());
                } elseif ($this->filter === 'week') {
                    $query->whereBetween('a.waktu', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($this->filter === 'month') {
                    $query->whereMonth('a.waktu', now()->month)
                        ->whereYear('a.waktu', now()->year);
                }

                $result = $query->first();

                $emisi = $result->total_emisi_co2_kg ?? 0;   // Kg
                $cost = $result->total_kerugian_ribu_rp ?? 0; // ribu Rp

                // Tentukan LOS
                if ($emisi < 1000 && $cost < 50000) {
                    $los = 'A';
                } elseif ($emisi < 5000 && $cost < 200000) {
                    $los = 'B';
                } elseif ($emisi < 10000 && $cost < 500000) {
                    $los = 'C';
                } elseif ($emisi < 20000 && $cost < 1000000) {
                    $los = 'D';
                } else {
                    $los = 'E';
                }

                // Ambil peak jam (3 jam interval paling padat)
                if ($this->filter === 'today') {
                    $start = today()->toDateString();
                    $end = today()->toDateString();
                } elseif ($this->filter === 'week') {
                    $start = now()->startOfWeek()->toDateString();
                    $end = now()->endOfWeek()->toDateString();
                } elseif ($this->filter === 'month') {
                    $start = now()->startOfMonth()->toDateString();
                    $end = now()->endOfMonth()->toDateString();
                } else {
                    $start = today()->toDateString();
                    $end = today()->toDateString();
                }

                // Query MySQL-compatible untuk peak time
                $peak = DB::select("
            WITH volume_per_jam AS (
                SELECT 
                    DATE(waktu) AS tanggal,
                    HOUR(waktu) AS jam,
                    SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG) AS total_volume
                FROM arus
                WHERE DATE(waktu) BETWEEN ? AND ?
                GROUP BY DATE(waktu), HOUR(waktu)
            ),
            rentang_3jam AS (
                SELECT 
                    v1.tanggal,
                    v1.jam AS jam_mulai,
                    v1.jam + 2 AS jam_akhir,
                    v1.total_volume + COALESCE(v2.total_volume, 0) + COALESCE(v3.total_volume, 0) AS total_rentang
                FROM volume_per_jam v1
                LEFT JOIN volume_per_jam v2 
                    ON v1.tanggal = v2.tanggal AND v2.jam = v1.jam + 1
                LEFT JOIN volume_per_jam v3 
                    ON v1.tanggal = v3.tanggal AND v3.jam = v1.jam + 2
            )
            SELECT 
                CONCAT(LPAD(CAST(jam_mulai AS CHAR), 2, '0'), ':00') AS start_jam,
                CONCAT(LPAD(CAST(jam_akhir AS CHAR), 2, '0'), ':59') AS end_jam,
                total_rentang
            FROM rentang_3jam
            ORDER BY total_rentang DESC
            LIMIT 1
        ", [$start, $end]);

                $peakRange = null;
                if (!empty($peak) && isset($peak[0])) {
                    $peakRange = $peak[0]->start_jam . ":00 - " . $peak[0]->end_jam . ":59";
                    \Log::info('📌 Peak Range Ditemukan', [
                        'start_jam' => $peak[0]->start_jam,
                        'end_jam' => $peak[0]->end_jam,
                        'peakRange' => $peakRange,
                        'total' => $peak[0]->total_rentang ?? null,
                    ]);
                }
                \Log::info('show range', ['jam' => $peak]);
                // Set ke property class
                $this->cost = $cost;
                $this->los = $los;
                $this->peakTime = $peakRange;

                return [
                    'total' => $emisi,
                    'cost' => $cost,
                    'los' => $los,
                    'peak' => $peakRange,
                ];
            });


            $this->dispatch('emisiData', [
                'data' => $this->data_emisi,
                'cost' => $this->cost,
                'los' => $this->los,
                'peak' => $this->peakTime,
            ]);

        } finally {
            $this->isLoading = false;
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadData();
        $this->fetchData();
        $this->loadVehicleData();
        $this->dispatch('filterChanged', $filter, $this->selectedDate);
        $this->dispatch('$refresh');
        $this->dispatch('dataUpdated', $this->data);
        $this->dispatch('emisiData', [
            'data' => $this->data_emisi,
            'cost' => $this->cost,
            'los' => $this->los,
        ]);
        $this->dispatch('filterChanged', $this->filter);
    }
    public function loadVehicleData()
    {
        // Tentukan start & end date
        if ($this->filter === 'today') {
            $start = $end = Carbon::today()->format('Y-m-d');
        } elseif ($this->filter === 'week') {
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        } elseif ($this->filter === 'month') {
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $start = $end = Carbon::today()->format('Y-m-d');
        }

        $data = VehicleQuery::getVehicleData($start, $end);

        if (!$data) {
            $data = (object) [];
        }

        $vehicleTypes = [
            'SM' => 'Motor',
            'MP' => 'Mobil',
            'BS' => 'Bus',
            'TR' => 'Truk',
            'AUP' => 'AUP',
            'TB' => 'TB',
            'BB' => 'BB',
            'GANDENG' => 'Gandeng',
            'KTB' => 'KTB',
        ];

        // Ikon per jenis kendaraan
        // $vehicleIcons = [
        //     'SM' => 'bike',
        //     'MP' => 'car',
        //     'BS' => 'bus',
        //     'TR' => 'truck',
        //     'AUP' => 'car-front',
        //     'TB' => 'truck',
        //     'BB' => 'package',
        //     'GANDENG' => 'truck',
        //     'KTB' => 'truck',
        // ];

        $incoming = [];
        $outgoing = [];

        foreach ($vehicleTypes as $col => $label) {
            $incoming[] = (int) ($data->{$col . '_masuk'} ?? 0);
            $outgoing[] = (int) ($data->{$col . '_keluar'} ?? 0);
        }

        $incomingTotal = array_sum($incoming);
        $outgoingTotal = array_sum($outgoing);
        $this->vehicleData = [
            'incomingVehicles' => [
                'labels' => array_values($vehicleTypes),
                'values' => $incoming,
                'percentages' => array_map(fn($v) => $incomingTotal ? round($v / $incomingTotal * 100) . '%' : '0%', $incoming),
                // 'iconComponents' => array_values($vehicleIcons),
            ],
            'outgoingVehicles' => [
                'labels' => array_values($vehicleTypes),
                'values' => $outgoing,
                'percentages' => array_map(fn($v) => $outgoingTotal ? round($v / $outgoingTotal * 100) . '%' : '0%', $outgoing),
                // 'iconComponents' => array_values($vehicleIcons),
            ]
        ];

        // Emit ke chart Livewire
        $this->dispatch('vehicleDataUpdated', $this->vehicleData);
    }

    public function loadData()
    {
        if ($this->filter === 'today') {
            $this->data = [
                'carbon' => 1250,
                'service' => "A",
                'peak' => "00:00 - 00:00",
                'cost' => 200000,
            ];
        } elseif ($this->filter === 'week') {
            $this->data = [
                'carbon' => 150,
                'service' => "A",
                'peak' => "00:00 - 00:00",
                'cost' => 13020,
            ];
        } elseif ($this->filter === 'month') {
            $this->data = [
                'carbon' => 1550,
                'service' => "B",
                'peak' => "00:00 - 00:00",
                'cost' => 201000,
            ];
        }
    }

    public function changeData()
    {
        $this->title = "word";
    }

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadData();
        $this->loadVehicleData();
        $this->fetchData();
        $this->tanggal = today()->toDateString();
    }

    public function render()
    {
        return view('livewire.dashboard-new');
    }

    public function hydrate()
    {
        if (empty($this->data_emisi)) {
            $this->fetchData();
        }
    }

}
