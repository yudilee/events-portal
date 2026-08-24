<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventCommentAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::select('id', 'title')->orderBy('date', 'desc')->get();
        $selectedEventId = $request->query('event_id');

        $query = EventComment::with(['event', 'user'])->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $perPage = $request->input('per_page', 20);
        if ($perPage === 'all' || (is_numeric($perPage) && (int)$perPage >= 9999)) {
            $comments = $query->paginate(99999)->withQueryString();
        } else {
            $comments = $query->paginate(is_numeric($perPage) ? max(1, min(500, (int)$perPage)) : 20)->withQueryString();
        }

        return Inertia::render('Admin/Wishes/Index', [
            'comments' => $comments,
            'events' => $events,
            'selectedEventId' => $selectedEventId ? (int)$selectedEventId : null,
        ]);
    }

    public function toggleApproval(int $id): RedirectResponse
    {
        $comment = EventComment::findOrFail($id);
        $comment->is_approved = !$comment->is_approved;
        $comment->save();

        return back()->with('success', 'Wish approval status updated.');
    }

    public function toggleHighlight(int $id): RedirectResponse
    {
        $comment = EventComment::findOrFail($id);
        $comment->is_highlighted = !$comment->is_highlighted;
        $comment->save();

        return back()->with('success', 'Wish highlight status updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $comment = EventComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Wish message removed.');
    }
}
