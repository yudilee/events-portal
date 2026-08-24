<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Event::with('businessUnit')->published();

        if ($request->filled('unit')) {
            $query->whereHas('businessUnit', function ($q) use ($request) {
                $q->where('slug', $request->unit);
            });
        }

        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('date', 'asc')->get();
        $businessUnits = BusinessUnit::orderBy('sort_order')->get();

        return Inertia::render('Public/Events/Index', [
            'events' => $events,
            'businessUnits' => $businessUnits,
            'filters' => $request->only(['unit', 'type', 'search']),
        ]);
    }

    public function show(string $slug): Response
    {
        $event = Event::with([
            'businessUnit',
            'agendas',
            'creator',
            'galleryMedia' => fn ($q) => $q->take(8),
            'highlightedComments' => fn ($q) => $q->take(4),
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $event->append(['confirmed_registrations_count', 'is_full']);

        return Inertia::render('Public/Events/Show', [
            'event' => $event,
            'galleryPreview' => $event->galleryMedia,
            'highlightedWishes' => $event->highlightedComments,
        ]);
    }

    public function gallery(string $slug): Response
    {
        $event = Event::with(['businessUnit', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Events/Gallery', [
            'event' => $event,
            'media' => $event->media,
        ]);
    }

    public function recap(string $slug): Response
    {
        $event = Event::with([
            'businessUnit',
            'postEventMedia',
            'galleryMedia',
            'comments' => fn ($q) => $q->with('user'),
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $event->append(['confirmed_registrations_count']);

        return Inertia::render('Public/Events/Recap', [
            'event' => $event,
            'postMedia' => $event->postEventMedia,
            'galleryMedia' => $event->galleryMedia,
            'comments' => $event->comments,
        ]);
    }

    public function register(string $slug): Response
    {
        $event = Event::with('businessUnit')
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$event->is_registration_enabled || $event->is_full) {
            return Inertia::render('Public/Events/Show', [
                'event' => $event,
                'flash' => ['error' => 'Registration is currently closed or at full capacity.'],
            ]);
        }

        return Inertia::render('Public/Events/Register', [
            'event' => $event,
        ]);
    }

    public function storeRegistration(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (!$event->is_registration_enabled || $event->is_full) {
            return back()->with('error', 'Registration for this event is closed or capacity is full.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'company' => 'nullable|string|max:255',
            'attendee_type' => 'required|string|in:general_guest,vip,media,partner',
            'num_attendees' => 'required|integer|min:1|max:10',
            'companions' => 'nullable|array',
            'companions.*.full_name' => 'required_with:companions|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'license_plate' => 'nullable|string|max:50',
            'media_outlet_name' => 'nullable|string|max:255',
            'dietary_notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $primaryData = [
            'event_id' => $event->id,
            'user_id' => $request->user()?->id,
            'parent_registration_id' => null,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'attendee_type' => $validated['attendee_type'],
            'num_attendees' => $validated['num_attendees'],
            'vehicle_model' => $validated['vehicle_model'] ?? null,
            'license_plate' => $validated['license_plate'] ?? null,
            'media_outlet_name' => $validated['media_outlet_name'] ?? null,
            'dietary_notes' => $validated['dietary_notes'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'confirmed',
        ];

        $primary = Registration::create($primaryData);

        // Create individual registration for each companion attendee so each person gets their own QR code and Guestbook eligibility!
        $companions = $request->input('companions', []);
        if (is_array($companions) && $validated['num_attendees'] > 1) {
            foreach ($companions as $companion) {
                if (!empty($companion['full_name'])) {
                    Registration::create([
                        'event_id' => $event->id,
                        'user_id' => null,
                        'parent_registration_id' => $primary->id,
                        'full_name' => trim($companion['full_name']),
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'company' => $validated['company'] ?? null,
                        'attendee_type' => $validated['attendee_type'],
                        'num_attendees' => 1,
                        'vehicle_model' => $validated['vehicle_model'] ?? null,
                        'license_plate' => $validated['license_plate'] ?? null,
                        'media_outlet_name' => $validated['media_outlet_name'] ?? null,
                        'dietary_notes' => $validated['dietary_notes'] ?? null,
                        'notes' => 'Companion attendee with ' . $primary->full_name,
                        'status' => 'confirmed',
                    ]);
                }
            }
        }

        return redirect()->route('public.events.ticket', [
            'slug' => $event->slug,
            'code' => $primary->registration_code,
        ])->with('success', 'Your RSVP has been confirmed! Save your digital E-Ticket(s) below.');
    }

    public function ticket(string $slug, string $code): Response
    {
        $event = Event::with('businessUnit')->where('slug', $slug)->firstOrFail();
        $registration = Registration::with(['event', 'companions', 'parentRegistration.companions'])
            ->where('event_id', $event->id)
            ->where('registration_code', $code)
            ->firstOrFail();

        // Gather all tickets in this group (parent + all companions)
        if ($registration->parent_registration_id) {
            $parent = $registration->parentRegistration;
            $allTickets = collect([$parent])->concat($parent->companions);
        } else {
            $allTickets = collect([$registration])->concat($registration->companions);
        }

        return Inertia::render('Public/Events/Ticket', [
            'event' => $event,
            'registration' => $registration,
            'groupTickets' => $allTickets->values(),
        ]);
    }
}
