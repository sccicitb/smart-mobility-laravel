<?php

namespace App\Livewire;

use Livewire\Component;

class ComingSoon extends Component
{
    public string $title = 'Coming Soon';

    public function render()
    {
        return view('livewire.coming-soon')
            ->title($this->title);
    }
}
