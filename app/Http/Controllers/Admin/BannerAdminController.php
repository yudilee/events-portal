<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BannerAdminController extends Controller
{
    public function index(): Response
    {
        $slides = HeroSlide::with('event')
            ->orderBy('sort_order', 'asc')
            ->get();

        return Inertia::render('Admin/Banners/Index', [
            'slides' => $slides,
        ]);
    }

    public function create(): Response
    {
        $events = Event::select('id', 'title', 'subtitle', 'slug', 'date', 'start_time', 'venue_name', 'hero_image')
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Admin/Banners/Form', [
            'slide' => null,
            'events' => $events,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:8192',
            'image_url' => 'nullable|string',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'secondary_cta_text' => 'nullable|string|max:100',
            'secondary_cta_url' => 'nullable|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'show_countdown' => 'boolean',
            'countdown_date' => 'nullable|date',
            'countdown_time' => 'nullable|string|max:20',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = asset('storage/' . $path);
        } elseif (!empty($validated['image_url'])) {
            $validated['image_path'] = $validated['image_url'];
        } else {
            $validated['image_path'] = 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1600&q=80';
        }

        unset($validated['image'], $validated['image_url']);

        HeroSlide::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Hero Carousel slide created successfully!');
    }

    public function edit(int $id): Response
    {
        $slide = HeroSlide::with('event')->findOrFail($id);
        $events = Event::select('id', 'title', 'subtitle', 'slug', 'date', 'start_time', 'venue_name', 'hero_image')
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Admin/Banners/Form', [
            'slide' => $slide,
            'events' => $events,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $slide = HeroSlide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:8192',
            'image_url' => 'nullable|string',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'secondary_cta_text' => 'nullable|string|max:100',
            'secondary_cta_url' => 'nullable|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'show_countdown' => 'boolean',
            'countdown_date' => 'nullable|date',
            'countdown_time' => 'nullable|string|max:20',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = asset('storage/' . $path);
        } elseif (!empty($validated['image_url'])) {
            $validated['image_path'] = $validated['image_url'];
        }

        unset($validated['image'], $validated['image_url']);

        $slide->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Hero Carousel slide updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Slide deleted successfully.');
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        return back()->with('success', 'Slide status updated.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $orders = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:hero_slides,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($orders['order'] as $item) {
            HeroSlide::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return back()->with('success', 'Slide order saved.');
    }
}
