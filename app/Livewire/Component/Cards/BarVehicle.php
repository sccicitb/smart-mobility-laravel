<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class BarVehicle extends Component
{
    public string $chartId = '';
    public bool $positionText = false;
    public array $chartData = [];

    public function mount(string $id = '', bool $positionText = false, array $chartData = [])
    {
        $this->chartId      = $id ?: 'bar-chart-' . str_replace('.', '', microtime(true));
        $this->positionText = $positionText;
        $this->chartData    = $chartData;
    }

    public function render()
    {
        return view('livewire.component.cards.bar-vehicle');
    }
}