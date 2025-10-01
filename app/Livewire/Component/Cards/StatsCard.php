<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class StatsCard extends Component
{
    // Fix: Use camelCase for event listener methods
    protected $listeners = ['dataUpdated', 'emisiData'];

    public $title;
    public $value;
    public $icon;
    public $unit;

    public function mount($title = "Default Title", $value = 0, $unit = "", $icon = "activity")
    {
        $this->title = $title;
        $this->value = $value;
        $this->unit = $unit;
        $this->icon = $icon;
    }

    // Event listener for emisi-data dispatch
    public function emisiData($payload)
    {
        if (strtolower($this->title) === 'carbon emissions') {
            $this->value = $payload['data']['total'] ?? 0;
        }
        // if (strtolower($this->title) === 'total losses') {
        //     $this->value = $payload['data']['los'] ?? 0;
        // }
        // if (strtolower($this->title) === 'total losses') {
        //     $this->value = $payload['data']['cos'] ?? 0;
        // }
    }
    public string|null $link = null;

    // Event listener for dataUpdated dispatch
    public function dataUpdated($data)
    {
        // if (strtolower($this->title) === 'carbon emissions') {
        //     $this->value = $data['carbon'] ?? 0;
        // }
        // Add more logic as needed for other card types
    }

    public function render()
    {
        return view('livewire.component.cards.stats-card');
    }
}