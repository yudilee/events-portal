<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\EventAgenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class EventAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $perPage = $request->input('per_page', 15);
        $perPageInt = ($perPage === 'all' || (is_numeric($perPage) && (int)$perPage >= 9999)) ? 99999 : (is_numeric($perPage) ? max(1, min(500, (int)$perPage)) : 15);

        $events = Event::with('businessUnit')
            ->withCount(['registrations as attendees_count' => function ($q) {
                $q->whereIn('status', ['confirmed', 'attended']);
            }])
            ->withCount(['registrations as checked_in_count' => function ($q) {
                $q->whereNotNull('checked_in_at');
            }])
            ->orderBy('date', 'desc')
            ->paginate($perPageInt)
            ->withQueryString();

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
        ]);
    }

    public function create(): Response
    {
        $businessUnits = BusinessUnit::orderBy('sort_order')->get();

        return Inertia::render('Admin/Events/Form', [
            'event' => null,
            'businessUnits' => $businessUnits,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_unit_id' => 'nullable|exists:business_units,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'content_html' => 'nullable|string',
            'event_type' => 'required|string',
            'visibility' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'timezone' => 'required|string',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'venue_map_url' => 'nullable|string',
            'dress_code' => 'required|string|max:100',
            'rsvp_contact' => 'nullable|string|max:100',
            'rsvp_phone' => 'nullable|string|max:50',
            'max_capacity' => 'required|integer|min:1',
            'registration_deadline' => 'nullable|date',
            'status' => 'required|string|in:draft,published,rescheduled,ongoing,completed,archived',
            'reschedule_notice' => 'nullable|string',
            'original_date' => 'nullable|date',
            'is_date_tba' => 'boolean',
            'is_registration_enabled' => 'boolean',
            'is_guestbook_enabled' => 'boolean',
            'is_gallery_enabled' => 'boolean',
            'is_post_event_enabled' => 'boolean',
            'agendas' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['created_by'] = $request->user()->id;

        $agendasData = $validated['agendas'] ?? [];
        unset($validated['agendas']);

        $event = Event::create($validated);

        if (!empty($agendasData)) {
            foreach ($agendasData as $index => $agenda) {
                if (!empty($agenda['title'])) {
                    EventAgenda::create([
                        'event_id' => $event->id,
                        'time_start' => $agenda['time_start'] ?? '10:00',
                        'time_end' => $agenda['time_end'] ?? null,
                        'title' => $agenda['title'],
                        'description' => $agenda['description'] ?? null,
                        'speaker_name' => $agenda['speaker_name'] ?? null,
                        'speaker_role' => $agenda['speaker_role'] ?? null,
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event successfully created!');
    }

    public function edit(int $id): Response
    {
        $event = Event::with(['businessUnit', 'agendas'])->findOrFail($id);
        $businessUnits = BusinessUnit::orderBy('sort_order')->get();

        return Inertia::render('Admin/Events/Form', [
            'event' => $event,
            'businessUnits' => $businessUnits,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'business_unit_id' => 'nullable|exists:business_units,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'content_html' => 'nullable|string',
            'event_type' => 'required|string',
            'visibility' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'timezone' => 'required|string',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'venue_map_url' => 'nullable|string',
            'dress_code' => 'required|string|max:100',
            'rsvp_contact' => 'nullable|string|max:100',
            'rsvp_phone' => 'nullable|string|max:50',
            'max_capacity' => 'required|integer|min:1',
            'registration_deadline' => 'nullable|date',
            'status' => 'required|string|in:draft,published,rescheduled,ongoing,completed,archived',
            'reschedule_notice' => 'nullable|string',
            'original_date' => 'nullable|date',
            'is_date_tba' => 'boolean',
            'is_registration_enabled' => 'boolean',
            'is_guestbook_enabled' => 'boolean',
            'is_gallery_enabled' => 'boolean',
            'is_post_event_enabled' => 'boolean',
            'agendas' => 'nullable|array',
        ]);

        if ($validated['status'] === 'rescheduled' && empty($event->original_date)) {
            $validated['original_date'] = $event->date;
        }

        $agendasData = $validated['agendas'] ?? [];
        unset($validated['agendas']);

        $event->update($validated);

        if (isset($agendasData)) {
            $event->agendas()->delete();
            foreach ($agendasData as $index => $agenda) {
                if (!empty($agenda['title'])) {
                    EventAgenda::create([
                        'event_id' => $event->id,
                        'time_start' => $agenda['time_start'] ?? '10:00',
                        'time_end' => $agenda['time_end'] ?? null,
                        'title' => $agenda['title'],
                        'description' => $agenda['description'] ?? null,
                        'speaker_name' => $agenda['speaker_name'] ?? null,
                        'speaker_role' => $agenda['speaker_role'] ?? null,
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    public function reschedule(Request $request, int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'is_date_tba' => 'boolean',
            'date' => 'nullable|date',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'venue_map_url' => 'nullable|string',
            'registration_deadline' => 'nullable|date',
            'reschedule_notice' => 'required|string|max:1000',
        ]);

        if (empty($event->original_date)) {
            $validated['original_date'] = $event->date;
        }

        if (empty($validated['date'])) {
            $validated['date'] = $event->date;
        }

        $validated['status'] = 'rescheduled';

        $event->update($validated);

        $dateMsg = !empty($validated['is_date_tba']) ? 'To Be Announced Shortly (TBA)' : \Carbon\Carbon::parse($validated['date'])->format('d M Y');

        return back()->with('success', "Event '{$event->title}' has been rescheduled. New schedule: {$dateMsg}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
