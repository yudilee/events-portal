<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventCommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => 'required|string|max:100',
            'guest_company' => 'nullable|string|max:100',
            'guest_role' => 'nullable|string|max:100',
            'message' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:8192',
            'type' => 'nullable|string|in:wish,afterthought,general',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store("events/{$event->id}/wishes", 'public');
        }

        EventComment::create([
            'event_id' => $event->id,
            'user_id' => $request->user()?->id,
            'guest_name' => $validated['guest_name'],
            'guest_company' => $validated['guest_company'] ?? null,
            'guest_role' => $validated['guest_role'] ?? null,
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'type' => $validated['type'] ?? 'wish',
            'likes_count' => 0,
            'is_approved' => true, // auto-approved with admin moderation capability
            'is_highlighted' => false,
        ]);

        return back()->with('success', 'Thank you! Your wish and thoughts have been shared with the community.');
    }

    public function like(int $commentId): RedirectResponse
    {
        $comment = EventComment::findOrFail($commentId);
        $comment->increment('likes_count');

        return back();
    }
}
