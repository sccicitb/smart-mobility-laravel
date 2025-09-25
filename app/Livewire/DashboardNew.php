<?php

namespace App\Livewire;
use Livewire\Component;
// use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
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

    public function fetchData()
    {
        $key = "emisi_{$this->filter}";
    
        $this->data_emisi = Cache::remember($key, now()->addMinutes(10), function () {
            $query = DB::table('arus as a')
                ->leftJoin('jarak_simpang as j', function ($join) {
                    $join->on('a.ID_Simpang', '=', 'j.ID_Simpang')
                        ->on('a.dari_arah', '=', 'j.dari_arah')
                        ->on('a.ke_arah', '=', 'j.ke_arah')
                        ->where('j.status', '=', 'aktif');
                })
                ->selectRaw("SUM(
                    (a.SM * 80 * j.jarak_km) + 
                    (a.MP * 180 * j.jarak_km) + 
                    (a.AUP * 350 * j.jarak_km) + 
                    (a.TR * 250 * j.jarak_km) + 
                    (a.BS * 800 * j.jarak_km) + 
                    (a.TS * 400 * j.jarak_km) + 
                    (a.TB * 600 * j.jarak_km) + 
                    (a.BB * 1200 * j.jarak_km) + 
                    (a.GANDENG * 900 * j.jarak_km)
                ) / 1000 AS total_emisi_co2_kg,
                
                SUM(
                    (a.SM * 10 + a.MP * 20 + 
                     (a.AUP + a.TR + a.BS + a.TS + a.TB + a.BB + a.GANDENG) * 40
                    ) * j.jarak_km
                ) AS total_kerugian_ribu_rp
                ")
                ->whereNotNull('j.jarak_km');
    
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
            $cost  = $result->total_kerugian_ribu_rp ?? 0; // ribu Rp
    
            // ✅ Tentukan LOS
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
    
            $this->cost = $cost;
            $this->los  = $los;
    
            return [
                'total' => $emisi,
                'cost'  => $cost,
                'los'   => $los,
            ];
        });
    
        $this->dispatch('emisiData', [
            'data' => $this->data_emisi,
            'cost' => $this->cost,
            'los'  => $this->los, 
        ]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadData();
        $this->fetchData();
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
    public function changeDate($newDate)
    {
        $this->selectedDate = $newDate;
        
        // Emit ke semua chart components
        $this->dispatch('filterChanged', $this->filter, $this->selectedDate);
    }
    public function loadVehicleData()
    {
        $this->vehicleData = [
            'incomingVehicles' => [
                'labels' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'values' => [120, 90, 15, 8],
                'percentages' => ['55%', '35%', '7%', '3%'],
                'vehicleTypes' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'tooltipLabels' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'color' => '#bd3a39',
                'directionRoad' => ['Utara', 'Timur', 'Selatan', 'Barat'],
                // kalau mau pakai icon tinggal kasih HTML / SVG string di sini
                'iconComponents' => [
                    '<i class="fa-solid fa-motorcycle"></i>',
                    '<i class="fa-solid fa-car"></i>',
                    '<i class="fa-solid fa-bus"></i>',
                    '<i class="fa-solid fa-truck"></i>',
                ]
            ],
            'outgoingVehicles' => [
                'labels' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'values' => [110, 70, 12, 5],
                'percentages' => ['58%', '30%', '8%', '4%'],
                'vehicleTypes' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'tooltipLabels' => ['Motor', 'Mobil', 'Bus', 'Truk'],
                'color' => '#bd3a39',
                'directionRoad' => ['Barat', 'Selatan', 'Timur', 'Utara'],
                'iconComponents' => [
                    '<i class="fa-solid fa-motorcycle"></i>',
                    '<i class="fa-solid fa-car"></i>',
                    '<i class="fa-solid fa-bus"></i>',
                    '<i class="fa-solid fa-truck"></i>',
                ]
            ]
        ];
    }

    public function loadData()
    {
        if ($this->filter === 'today') {
            $this->data = [
                'carbon' => 1250,
                'service' => "A",
                'peak' => "10:00 - 12:00",
                'cost' => 200000,
            ];
        } elseif ($this->filter === 'week') {
            $this->data = [
                'carbon' => 150,
                'service' => "A",
                'peak' => "10:00 - 12:00",
                'cost' => 13020,
            ];
        } elseif ($this->filter === 'month') {
            $this->data = [
                'carbon' => 1550,
                'service' => "B",
                'peak' => "10:00 - 12:00",
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
        $this->tanggal = today()->toDateString();
        // jangan langsung fetchData()
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
