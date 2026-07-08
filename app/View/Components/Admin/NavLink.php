<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class NavLink extends Component
{
    public function __construct(
        public string $href,
        public bool $active = false,
        public ?string $icon = null,
    ) {}

    public function render()
    {
        return view('components.admin.nav-link');
    }
}
