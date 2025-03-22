<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('components.layout');
    }
}