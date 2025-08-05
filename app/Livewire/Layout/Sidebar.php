<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Livewire\Attributes\On;

class Sidebar extends Component
{
    public $collapsed = false;

    public function mount()
    {
        $this->collapsed = session()->get('sidebar_collapsed', false);
    }

    #[On('toggleSidebar')]
    public function toggleSidebar()
    {
        $this->collapsed = !$this->collapsed;
        session()->put('sidebar_collapsed', $this->collapsed);
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
