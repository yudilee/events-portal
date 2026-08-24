<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GuestBookEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GuestBookAdminController extends Controller
{
    public function index(Request $request, ?int $eventId = null): Response
    {
        $events = Event::orderBy('date', 'desc')->get();
        $selectedEvent = $eventId ? Event::findOrFail($eventId) : $events->first();

        $query = GuestBookEntry::with('event');

        if ($selectedEvent) {
            $query->where('event_id', $selectedEvent->id);
        }

        $perPage = $request->input('per_page', 25);
        if ($perPage === 'all' || (is_numeric($perPage) && (int)$perPage >= 9999)) {
            $entries = $query->latest()->paginate(99999)->withQueryString();
        } else {
            $entries = $query->latest()->paginate(is_numeric($perPage) ? max(1, min(500, (int)$perPage)) : 25)->withQueryString();
        }

        return Inertia::render('Admin/GuestBook/Index', [
            'events' => $events,
            'selectedEvent' => $selectedEvent,
            'entries' => $entries,
        ]);
    }

    public function toggleApproval(int $id): RedirectResponse
    {
        $entry = GuestBookEntry::findOrFail($id);
        $entry->update(['is_approved' => !$entry->is_approved]);

        return back()->with('success', 'Guestbook entry moderation status updated.');
    }

    public function toggleHighlight(int $id): RedirectResponse
    {
        $entry = GuestBookEntry::findOrFail($id);
        $entry->update(['is_highlighted' => !$entry->is_highlighted]);

        return back()->with('success', 'Guestbook entry highlight toggled.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $entry = GuestBookEntry::findOrFail($id);
        $entry->delete();

        return back()->with('success', 'Message deleted.');
    }
}
