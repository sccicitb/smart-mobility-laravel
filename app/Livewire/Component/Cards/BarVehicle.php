<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class BarVehicle extends Component
{
    public $chartId;
    public $positionText = false;
    public $chartData = [];

    protected $listeners = ['vehicleDataUpdated'];

    public function mount($id = null, $positionText = false, $chartData = [])
    {
        // Ensure chartId is always set and unique
        // Use a more specific prefix to avoid conflicts
        $this->chartId = $id ?? 'bar-chart-' . uniqid() . '-' . rand(1000, 9999);
        $this->positionText = $positionText;
        $this->chartData = $chartData;
    }

    public function vehicleDataUpdated($data)
    {
        $this->chartData = $data;

        // Make sure chartId is set before dispatching
        if (empty($this->chartId)) {
            $this->chartId = 'bar-chart-' . uniqid();
        }

        // Dispatch as a direct object, not wrapped in an array
        $this->dispatch('bar-chart-updated', 
            id: $this->chartId,
            data: $this->chartData,
            positionText: $this->positionText
        );
    }

    public function render()
    {
        return view('livewire.component.cards.bar-vehicle');
    }
}