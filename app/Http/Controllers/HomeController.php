<?php

namespace App\Http\Controllers;

use App\Models\CompanyStatistic;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\PageSection;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::published()->ordered()->get();
        $stats = CompanyStatistic::ordered()->get();
        $whyChooseUs = PageSection::getByKey('why_choose_us');
        $contactCta = PageSection::getByKey('home_contact_cta');
        $processSteps = ProcessStep::ordered()->get();

        $services = Service::published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->limit(6)
            ->get();

        $projects = Project::published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::published()
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->limit(6)
            ->get();

        $faqs = Faq::published()->orderBy('order')->limit(6)->get();

        return view('home', compact(
            'heroSlides', 'stats', 'whyChooseUs', 'contactCta', 'processSteps',
            'services', 'projects', 'testimonials', 'faqs',
        ));
    }
}
