<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MetaTags extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $metaTagContent;
    public $ogUrl;
    public $ogImage;
    public $pageHeading;

    public function __construct($metaTagContent, $ogUrl = null, $ogImage = null, $pageHeading = null)
    {
        $this->metaTagContent = $metaTagContent;
        $this->ogUrl = $ogUrl ?? url()->current();
        $this->ogImage = $ogImage;
        $this->pageHeading = $pageHeading;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.meta-tags');
    }
}
