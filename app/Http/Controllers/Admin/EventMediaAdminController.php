<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EventMediaAdminController extends Controller
{
    public function index(int $eventId): Response
    {
        $event = Event::with(['businessUnit', 'media'])->findOrFail($eventId);

        return Inertia::render('Admin/Events/Media', [
            'event' => $event,
            'media' => $event->media,
        ]);
    }

    public function store(Request $request, int $eventId): RedirectResponse
    {
        $event = Event::findOrFail($eventId);

        $request->validate([
            'files.*' => 'required_without:media_url|image|max:10240',
            'media_url' => 'nullable|string|max:500',
            'collection' => 'required|string|in:gallery,post_event,hero_slide',
            'caption' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:image,video_url',
        ]);

        $collection = $request->input('collection', 'gallery');

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $currentMaxOrder = $event->media()->max('sort_order') ?? 0;

            foreach ($files as $index => $file) {
                $path = $file->store("events/{$event->id}/media", 'public');

                EventMedia::create([
                    'event_id' => $event->id,
                    'uploaded_by' => $request->user()->id,
                    'type' => 'image',
                    'collection' => $collection,
                    'file_path' => $path,
                    'caption' => $request->input('caption'),
                    'sort_order' => $currentMaxOrder + $index + 1,
                    'is_featured' => false,
                ]);
            }
        } elseif ($request->filled('media_url')) {
            $currentMaxOrder = $event->media()->max('sort_order') ?? 0;

            EventMedia::create([
                'event_id' => $event->id,
                'uploaded_by' => $request->user()->id,
                'type' => $request->input('type', 'video_url'),
                'collection' => $collection,
                'file_path' => $request->input('media_url'),
                'caption' => $request->input('caption'),
                'sort_order' => $currentMaxOrder + 1,
                'is_featured' => false,
            ]);
        }

        return back()->with('success', 'Media items uploaded successfully!');
    }

    public function destroy(int $mediaId): RedirectResponse
    {
        $media = EventMedia::findOrFail($mediaId);
        
        // Remove from storage if stored locally
        if (!str_starts_with($media->file_path, 'http://') && !str_starts_with($media->file_path, 'https://')) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return back()->with('success', 'Media item removed successfully.');
    }

    public function toggleFeatured(int $mediaId): RedirectResponse
    {
        $media = EventMedia::findOrFail($mediaId);
        $media->is_featured = !$media->is_featured;
        $media->save();

        return back()->with('success', 'Featured status updated.');
    }

    public function updateCaption(Request $request, int $mediaId): RedirectResponse
    {
        $media = EventMedia::findOrFail($mediaId);
        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'collection' => 'required|string|in:gallery,post_event,hero_slide',
        ]);

        $media->update($validated);

        return back()->with('success', 'Media details updated.');
    }
}
