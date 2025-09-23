<?php

namespace App\Livewire\Component\Cards;

use Livewire\Component;

class VideoPlayer extends Component
{
    public $src;
    public $poster;
    public $playerId;

    public function mount($src, $poster = null)
    {
        $this->src = $src;
        $this->poster = $poster;
        // bikin ID unik untuk player
        $this->playerId = 'video-player-' . uniqid();
    }

    public function render()
    {
        return view('livewire.component.cards.video-player');
    }
}
