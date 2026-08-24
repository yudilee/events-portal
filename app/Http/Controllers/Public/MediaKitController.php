<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Inertia\Inertia;
use Inertia\Response;

class MediaKitController extends Controller
{
    public function index(): Response
    {
        $businessUnits = BusinessUnit::orderBy('sort_order')->get();

        return Inertia::render('Public/Media/Index', [
            'businessUnits' => $businessUnits,
        ]);
    }
}
