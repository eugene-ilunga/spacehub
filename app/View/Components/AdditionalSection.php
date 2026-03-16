<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdditionalSection extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $sections;
    public $position;

    public function __construct($sections, $position)
    {
        $this->sections = $sections;
        $this->position = $position;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.additional-section');
    }
}
