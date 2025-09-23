<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;
class StatsCard extends Component
{
    protected $listeners = ['dataUpdated'];
    // biar reusable, kita pakai props (public variable)
    public $title;
    public $value;
    public $icon;
    public $unit;

    
    public function dataUpdated($data)
    {   
        // Update value berdasarkan title
        // Logic untuk menentukan value mana yang harus digunakan
        
    }
    // public function mount(
    //     $title = "Karbon Emisi",
    //     $value = 0,
    //     $unit = "kg",
    //     $icon = "trash-2" // default ikon dari Lucide
    // ) {
    //     $this->title = $title;
    //     $this->value = $value;
    //     $this->unit = $unit;
    //     $this->icon = $icon;
    // }
    public function render()
    {
        return view('livewire.component.cards.stats-card');
    }
}
