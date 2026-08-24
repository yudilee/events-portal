<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\PressRelease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NewsAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $perPage = $request->input('per_page', 15);
        $perPageInt = ($perPage === 'all' || (is_numeric($perPage) && (int)$perPage >= 9999)) ? 99999 : (is_numeric($perPage) ? max(1, min(500, (int)$perPage)) : 15);

        $releases = PressRelease::with(['businessUnit', 'event'])
            ->latest('created_at')
            ->paginate($perPageInt)
            ->withQueryString();

        return Inertia::render('Admin/News/Index', [
            'releases' => $releases,
        ]);
    }

    public function create(): Response
    {
        $businessUnits = BusinessUnit::all();
        $events = Event::orderBy('date', 'desc')->get();

        return Inertia::render('Admin/News/Form', [
            'release' => null,
            'businessUnits' => $businessUnits,
            'events' => $events,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_unit_id' => 'nullable|exists:business_units,id',
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'visibility' => 'required|string|in:public,media_only',
            'is_embargoed' => 'boolean',
            'embargo_until' => 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        PressRelease::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Press release published successfully!');
    }

    public function edit(int $id): Response
    {
        $release = PressRelease::findOrFail($id);
        $businessUnits = BusinessUnit::all();
        $events = Event::orderBy('date', 'desc')->get();

        return Inertia::render('Admin/News/Form', [
            'release' => $release,
            'businessUnits' => $businessUnits,
            'events' => $events,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $release = PressRelease::findOrFail($id);

        $validated = $request->validate([
            'business_unit_id' => 'nullable|exists:business_units,id',
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'visibility' => 'required|string|in:public,media_only',
            'is_embargoed' => 'boolean',
            'embargo_until' => 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        $release->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Press release updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $release = PressRelease::findOrFail($id);
        $release->delete();

        return redirect()->route('admin.news.index')->with('success', 'Press release deleted.');
    }
}
