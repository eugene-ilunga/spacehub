<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class VideoBanner extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $status;
    public $image;
    public $videoLink;
    public $variant;

    public function __construct($status, $image = null, $videoLink = null, $variant = 'home-1')
    {
        $this->status = $status;
        $this->image = $image;
        $this->videoLink = $videoLink;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.video-banner');
    }
}
