<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;
use Livewire\Attributes\Reactive;

class StatsCard extends Component
{
    public string $title = 'Default Title';

    #[Reactive]
    public string|int|float $value = 0;
    public string $icon  = 'activity';
    public string $unit  = '';
    public string|null $link = null;

    public function mount(
        string $title = 'Default Title',
        string|int|float $value = 0,
        string $unit = '',
        string $icon = 'activity',
        string|null $link = null,
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->unit  = $unit;
        $this->icon  = $icon;
        $this->link  = $link;
    }

    public function render()
    {
        return view('livewire.component.cards.stats-card');
    }
}