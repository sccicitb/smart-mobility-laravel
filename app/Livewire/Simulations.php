<?php

namespace App\Livewire;

use Livewire\Component;

class Simulations extends Component
{
    // Data simulasi
    public $simulations = [
        // [
        //     'id' => 1,
        //     'title' => 'Traffic Flow',
        //     'subtitle' => 'Smart Mobility',
        //     'description' => 'Simulation for efficiently managing traffic flow.',
        //     'image' => 'https://unsplash.com/photos/photo-of-roadway-between-buildings-XVUQfCkcaUg',
        //     'route' => 'traffic-flow'
        // ],
        // [
        //     'id' => 2,
        //     'title' => 'Congestion',
        //     'subtitle' => 'Smart Mobility',
        //     'description' => 'Simulation for addressing traffic congestion in urban areas.',
        //     'image' => 'https://unsplash.com/photos/a-crowded-street-filled-with-lots-of-traffic-VubGkfQjNFk',
        //     'route' => 'congestions'
        // ],
        [
            'id' => 1,
            'title' => 'Intersection',
            'subtitle' => 'Smart Mobility',
            'description' => 'Simulation for safely managing intersections.',
            'image' => 'https://unsplash.com/photos/aerial-photography-of-buildings-and-vehicles-OkOE0G_GC8Q',
            'route' => 'simulator'
        ],
        // [
        //     'id' => 4,
        //     'title' => 'Travel Time',
        //     'subtitle' => 'Smart Mobility',
        //     'description' => 'Simulation for estimating travel time on specific routes.',
        //     'image' => 'https://unsplash.com/photos/time-lapse-photography-of-road-during-nighttime-bi2UXH9GzJs',
        //     'route' => 'travel-times'
        // ],
    ];    

    public function render()
    {
        return view('livewire.simulations');
    }
}
