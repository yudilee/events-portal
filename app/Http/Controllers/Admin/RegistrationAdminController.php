<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegistrationAdminController extends Controller
{
    public function index(Request $request, ?int $eventId = null): Response
    {
        $events = Event::orderBy('date', 'desc')->get();
        $selectedEvent = $eventId ? Event::findOrFail($eventId) : $events->first();

        $query = Registration::with('event');

        if ($selectedEvent) {
            $query->where('event_id', $selectedEvent->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('attendee_type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('company', 'like', "%{$s}%")
                  ->orWhere('registration_code', 'like', "%{$s}%")
                  ->orWhere('vehicle_model', 'like', "%{$s}%");
            });
        }

        $registrations = $query->latest()->paginate(20);

        return Inertia::render('Admin/Registrations/Index', [
            'events' => $events,
            'selectedEvent' => $selectedEvent,
            'registrations' => $registrations,
            'filters' => $request->only(['status', 'type', 'search']),
        ]);
    }

    public function toggleCheckIn(Request $request, int $id): RedirectResponse
    {
        $registration = Registration::with('event')->findOrFail($id);

        if ($registration->checked_in_at) {
            $registration->update([
                'checked_in_at' => null,
                'checked_in_by' => null,
                'status' => 'confirmed',
            ]);
            $msg = "Check-in undone for {$registration->full_name}.";
        } else {
            $eventDate = \Carbon\Carbon::parse($registration->event->date)->startOfDay();
            $today = now()->startOfDay();

            if ($today->lt($eventDate) && !$request->boolean('override_early')) {
                return back()->with('error', "Check-in is only permitted on the day of the event (" . $eventDate->format('d M Y') . ").");
            }

            $registration->update([
                'checked_in_at' => now(),
                'checked_in_by' => $request->user()->id,
                'status' => 'attended',
            ]);
            $msg = "{$registration->full_name} marked as checked-in!";
        }

        return back()->with('success', $msg);
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $registration = Registration::findOrFail($id);
        $status = $request->validate(['status' => 'required|in:pending,confirmed,waitlist,cancelled,attended'])['status'];

        $registration->update(['status' => $status]);

        return back()->with('success', "Status updated to {$status}.");
    }

    public function exportCsv(int $eventId): StreamedResponse
    {
        $event = Event::findOrFail($eventId);
        $registrations = Registration::where('event_id', $event->id)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"registrations-{$event->slug}.csv\"",
        ];

        return response()->stream(function () use ($registrations) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Code', 'Full Name', 'Email', 'Phone', 'Company',
                'Attendee Type', 'Attendees Count', 'Vehicle Model', 'License Plate',
                'Media Outlet', 'Status', 'Checked In At', 'Created At'
            ]);

            foreach ($registrations as $r) {
                fputcsv($handle, [
                    $r->registration_code,
                    $r->full_name,
                    $r->email,
                    $r->phone,
                    $r->company,
                    $r->attendee_type,
                    $r->num_attendees,
                    $r->vehicle_model,
                    $r->license_plate,
                    $r->media_outlet_name,
                    $r->status,
                    $r->checked_in_at?->format('Y-m-d H:i:s'),
                    $r->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    public function destroy(int $id): RedirectResponse
    {
        $registration = Registration::findOrFail($id);
        $name = $registration->full_name;
        $registration->delete();

        return back()->with('success', "Registration for {$name} deleted successfully.");
    }
}
