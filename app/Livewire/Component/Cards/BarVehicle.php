<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class BarVehicle extends Component
{
    public $id;
    public $positionText = false;
    public $chartData = [];

    public function mount($id = null, $positionText = false, $chartData = [])
    {
        $this->id = $id ?? uniqid('bar-chart-');
        $this->positionText = $positionText;
        $this->chartData = $chartData;
    }

    public function render()
    {
        return view('livewire.component.cards.bar-vehicle');
    }
}