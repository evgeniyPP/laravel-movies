<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TvShowCard extends Component
{
    public $tvshow;

    public function __construct($tvshow)
    {
        $this->tvshow = $tvshow;
    }

    public function render()
    {
        return view('components.tv-show-card');
    }
}
