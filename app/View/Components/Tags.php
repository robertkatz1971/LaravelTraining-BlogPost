<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Tags extends Component
{
    public $tags;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tags = null)
    {
        $this->tags = $tags;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tags');
    }
}
