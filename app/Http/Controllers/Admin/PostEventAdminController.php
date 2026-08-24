<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostEventAdminController extends Controller
{
    public function edit(int $eventId): Response
    {
        $event = Event::with(['businessUnit', 'postEventMedia', 'allComments'])->findOrFail($eventId);

        return Inertia::render('Admin/Events/PostEvent', [
            'event' => $event,
            'postMedia' => $event->postEventMedia,
            'comments' => $event->allComments,
        ]);
    }

    public function update(Request $request, int $eventId): RedirectResponse
    {
        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'post_event_summary' => 'nullable|string',
            'is_post_event_enabled' => 'boolean',
            'publish_recap' => 'boolean',
        ]);

        $event->post_event_summary = $validated['post_event_summary'] ?? null;
        $event->is_post_event_enabled = $validated['is_post_event_enabled'] ?? true;

        if (!empty($validated['publish_recap']) && !$event->post_event_published_at) {
            $event->post_event_published_at = now();
            $event->status = 'completed';
        } elseif (empty($validated['publish_recap'])) {
            $event->post_event_published_at = null;
        }

        $event->save();

        return back()->with('success', 'Post-event recap & narrative updated successfully!');
    }
}
