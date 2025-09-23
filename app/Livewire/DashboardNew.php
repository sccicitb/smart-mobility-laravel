<?php

namespace App\Livewire;
use Livewire\Component;

class DashboardNew extends Component
{
    public $title = "Dashboard";
    public $titleBar = "Intersection Traffic Flow per Vehicle Type";
    public $descriptionWelcome = "Smart Mobility Simulator is a powerful tool that replicates traffic flow at intersections. It uses real-time traffic data from video analytics, allowing users to instantly manipulate variables like traffic light timing and lane width in a safe virtual environment. It's a virtual 'laboratory' to test scenarios without the risk or cost of physical implementation.";
    public $filter = 'today';
    public $data = [
        'carbon' => 0,
        'energy' => 0,
        'water' => 0,
    ];
    public $vehicleData = [];

    public function mount()
    {
        $this->loadData();
        $this->loadVehicleData();
    }
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadData();

        $this->dispatch('$refresh');
        $this->dispatch('dataUpdated', $this->data);
        $this->dispatch('filterChanged', $this->filter); 
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

    public function render()
    {
        return view('livewire.dashboard-new');
        // ->layout('layouts.app');
    }
}
