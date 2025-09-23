<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class ChartsCard extends Component
{
    public $filter; 
    public $chartType = 'bar';
    public $title = 'Chart Title';
    public $parentFilter; // ← Terima dari parent tapi beda nama
    public $data = [
        'carbon' => [],
        'energy' => [],
        'water' => []
    ];
    public $labels = [];
    public $chartId;

    // Listener untuk update dari parent
    
    protected $listeners = ['filterChanged' => 'updateFromParent'];

    public function mount($chartType = 'bar', $title = 'Analisis Data', $filter = 'today')
    {
        $this->chartType = $chartType;
        $this->title = $title;
        $this->parentFilter = $filter; // ← Simpan filter dari parent
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
    
    // Method untuk update dari parent
    public function updateFromParent($newFilter)
    {
        $this->parentFilter = $newFilter; // ← update parentFilter
        $this->loadChartData();
    
        logger()->info('FilterChanged diterima', ['filter' => $newFilter]);
    
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
        // Load data berdasarkan parentFilter (bukan filter biasa)
        switch ($this->parentFilter) {
            case 'today':
                $this->labels = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'];
                $this->data = [
                    'carbon' => [10, 25, 35, 50, 45, 40],
                    'energy' => [20, 30, 45, 60, 55, 50],
                    'water' => [15, 20, 25, 30, 35, 25]
                ];
                break;
            case 'week':
                $this->labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $this->data = [
                    'carbon' => [120, 150, 180, 200, 170, 160, 140],
                    'energy' => [200, 250, 300, 350, 280, 260, 220],
                    'water' => [100, 130, 150, 180, 160, 140, 120]
                ];
                break;
            case 'month':
                $this->labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                $this->data = [
                    'carbon' => [800, 950, 1100, 1200],
                    'energy' => [1500, 1800, 2100, 2400],
                    'water' => [600, 750, 900, 1000]
                ];
                break;
            default:
                $this->labels = [];
                $this->data = [
                    'carbon' => [],
                    'energy' => [],
                    'water' => []
                ];
        }
        
        \Log::debug("Chart data loaded for filter {$this->parentFilter}: ", [
            'labels' => $this->labels,
            'data' => $this->data
        ]);
    }

    public function render()
    {
        return view('livewire.component.cards.charts-card');
    }
}