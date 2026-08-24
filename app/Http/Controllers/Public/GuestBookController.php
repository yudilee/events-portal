<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GuestBookEntry;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GuestBookController extends Controller
{
    public function show(string $slug): Response
    {
        $event = Event::with('businessUnit')->where('slug', $slug)->firstOrFail();

        $entries = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->orderBy('is_highlighted', 'desc')
            ->latest()
            ->paginate(30);

        return Inertia::render('Public/Events/Guestbook', [
            'event' => $event,
            'entries' => $entries,
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (!$event->is_guestbook_enabled) {
            return back()->with('error', 'Guest book is currently closed for this event.');
        }

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000',
            'registration_code' => 'nullable|string|max:50',
        ]);

        $registrationId = null;
        if (!empty($validated['registration_code'])) {
            $reg = Registration::where('event_id', $event->id)
                ->where('registration_code', trim($validated['registration_code']))
                ->first();
            if ($reg) {
                $registrationId = $reg->id;
            }
        }

        GuestBookEntry::create([
            'event_id' => $event->id,
            'registration_id' => $registrationId,
            'guest_name' => $validated['guest_name'],
            'company' => $validated['company'] ?? null,
            'message' => $validated['message'],
            'is_approved' => true,
            'is_highlighted' => false,
        ]);

        return back()->with('success', 'Thank you! Your message has been posted on the event guestbook wall.');
    }
}
