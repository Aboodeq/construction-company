<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class NavSection extends Component
{
    public function __construct(
        public string $title,
    ) {}

    public function render()
    {
        return view('components.admin.nav-section');
    }
}
