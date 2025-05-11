<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;
    public $size;
    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user = null, $size = 'md', $class = '')
    {
        $this->user = $user;
        $this->size = $size;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.avatar');
    }
}