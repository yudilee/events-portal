<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GuestBookEntry;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
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

    public function kiosk(string $slug): Response
    {
        $event = Event::with('businessUnit')->where('slug', $slug)->firstOrFail();

        $recentEntries = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->latest()
            ->take(12)
            ->get(['id', 'guest_name', 'company', 'message', 'created_at']);

        $totalSigned = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->count();

        $qrUrl = route('public.events.guestbook.kiosk', $event->slug);

        return Inertia::render('Public/Events/GuestbookKiosk', [
            'event' => $event,
            'recentEntries' => $recentEntries,
            'totalSigned' => $totalSigned,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function qrStandee(string $slug): Response
    {
        $event = Event::with('businessUnit')->where('slug', $slug)->firstOrFail();

        $recentEntries = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->latest()
            ->take(15)
            ->get(['id', 'guest_name', 'company', 'message', 'created_at']);

        $totalSigned = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->count();

        $targetUrl = route('public.events.guestbook.kiosk', $event->slug);

        return Inertia::render('Public/Events/GuestbookQrStandee', [
            'event' => $event,
            'recentEntries' => $recentEntries,
            'totalSigned' => $totalSigned,
            'targetUrl' => $targetUrl,
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
            $c = trim($validated['registration_code']);
            $reg = Registration::where('event_id', $event->id)
                ->where(function ($q) use ($c) {
                    $q->where('registration_code', $c)
                      ->orWhere('phone', $c)
                      ->orWhere('email', $c);
                })
                ->first();
            if ($reg) {
                $registrationId = $reg->id;
                if (!$reg->checked_in_at) {
                    $reg->update([
                        'checked_in_at' => now(),
                        'status' => 'attended',
                    ]);
                }
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

    public function lookupTicket(Request $request, string $slug): JsonResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $code = trim($request->input('code', ''));

        if (preg_match('/ticket\/([A-Za-z0-9_-]+)/i', $code, $matches)) {
            $code = $matches[1];
        }

        $registration = Registration::where('event_id', $event->id)
            ->where(function ($q) use ($code) {
                $q->where('registration_code', $code)
                  ->orWhere('phone', $code)
                  ->orWhere('email', $code);
            })
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'code' => $code,
                'message' => "Ticket code '{$code}' not found for this event.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'registration' => [
                'id' => $registration->id,
                'full_name' => $registration->full_name,
                'company' => $registration->company ?? '',
                'email' => $registration->email ?? '',
                'phone' => $registration->phone ?? '',
                'registration_code' => $registration->registration_code,
                'attendee_type' => $registration->attendee_type,
                'vehicle_model' => $registration->vehicle_model,
                'license_plate' => $registration->license_plate,
                'checked_in' => !empty($registration->checked_in_at),
            ],
        ]);
    }
}
