<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\PressRelease;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = PressRelease::public()->with(['businessUnit', 'event']);

        if ($request->filled('unit')) {
            $query->whereHas('businessUnit', function ($q) use ($request) {
                $q->where('slug', $request->unit);
            });
        }

        $news = $query->latest('published_at')->paginate(12);
        $businessUnits = BusinessUnit::orderBy('sort_order')->get();

        return Inertia::render('Public/News/Index', [
            'news' => $news,
            'businessUnits' => $businessUnits,
            'filters' => $request->only('unit'),
        ]);
    }

    public function show(string $slug): Response
    {
        $article = PressRelease::public()
            ->with(['businessUnit', 'event'])
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views_count');

        $relatedNews = PressRelease::public()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Public/News/Show', [
            'article' => $article,
            'relatedNews' => $relatedNews,
        ]);
    }
}
