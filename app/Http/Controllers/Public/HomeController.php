<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\PressRelease;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $slides = HeroSlide::active()->with('event')->get();

        $featuredEvents = Event::with('businessUnit')
            ->published()
            ->upcoming()
            ->take(3)
            ->get();

        $businessUnits = BusinessUnit::withCount(['events' => function ($q) {
            $q->published();
        }])->orderBy('sort_order')->get();

        $latestNews = PressRelease::public()
            ->with('businessUnit')
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Public/Home', [
            'slides' => $slides,
            'featuredEvents' => $featuredEvents,
            'businessUnits' => $businessUnits,
            'latestNews' => $latestNews,
        ]);
    }
}
