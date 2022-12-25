<?php

namespace App\View\Components;

use Illuminate\View\Component;
use SebastianBergmann\Type\TrueType;

class Badge extends Component
{

    public $type;
    public $visible;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $visible = false, $type = 'success')
    {
        $this->type = $type;
        $this->visible = $visible;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.badge');
    }
}
