<?php

namespace App\View\Components;

use App\Models\CompanyStatistic;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(public ?string $title = null)
    {
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest', [
            'stats' => CompanyStatistic::ordered()->take(4)->get(),
        ]);
    }
}
