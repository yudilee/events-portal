<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Carbon\Carbon;

class CheckInController extends Controller
{
    public function index(Request $request, ?int $eventId = null): Response
    {
        $events = Event::upcoming()->published()->get();
        if ($events->isEmpty()) {
            $events = Event::orderBy('date', 'desc')->get();
        }

        $selectedEvent = $eventId ? Event::findOrFail($eventId) : $events->first();

        $stats = [
            'total' => 0,
            'attended' => 0,
            'remaining' => 0,
            'vip_count' => 0,
            'media_count' => 0,
        ];

        $recentCheckIns = [];
        $registrations = [];

        if ($selectedEvent) {
            $allRegistrationsQuery = Registration::where('event_id', $selectedEvent->id);

            $stats['total'] = (clone $allRegistrationsQuery)->count();
            $stats['attended'] = (clone $allRegistrationsQuery)->whereNotNull('checked_in_at')->count();
            $stats['remaining'] = max(0, $stats['total'] - $stats['attended']);
            $stats['vip_count'] = (clone $allRegistrationsQuery)->where('attendee_type', 'vip')->count();
            $stats['media_count'] = (clone $allRegistrationsQuery)->where('attendee_type', 'media')->count();

            $recentCheckIns = (clone $allRegistrationsQuery)
                ->whereNotNull('checked_in_at')
                ->latest('checked_in_at')
                ->take(10)
                ->get();

            $registrations = (clone $allRegistrationsQuery)
                ->select([
                    'id',
                    'registration_code',
                    'full_name',
                    'company',
                    'email',
                    'phone',
                    'attendee_type',
                    'num_attendees',
                    'vehicle_model',
                    'license_plate',
                    'status',
                    'checked_in_at',
                    'created_at',
                ])
                ->orderBy('full_name', 'asc')
                ->get();
        }

        return Inertia::render('Admin/CheckIn/Scanner', [
            'events' => $events,
            'selectedEvent' => $selectedEvent,
            'stats' => $stats,
            'recentCheckIns' => $recentCheckIns,
            'registrations' => $registrations,
        ]);
    }

    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'code' => 'required|string',
            'override_early' => 'nullable|boolean',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $eventDate = Carbon::parse($event->date)->startOfDay();
        $today = now()->startOfDay();

        // Check-in is only allowed on or after the event day
        if ($today->lt($eventDate) && !$request->boolean('override_early')) {
            return response()->json([
                'success' => false,
                'is_early' => true,
                'event_date' => $eventDate->format('l, d F Y'),
                'message' => "Check-in is only permitted on the day of the event (" . $eventDate->format('d M Y') . "). Early check-in is not opened yet.",
            ], 422);
        }

        $code = trim($validated['code']);

        $registration = Registration::where('event_id', $validated['event_id'])
            ->where(function ($q) use ($code) {
                $q->where('registration_code', $code)
                  ->orWhere('phone', $code)
                  ->orWhere('email', $code);
            })
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => "Registration code or phone '{$code}' not found for this event.",
            ], 404);
        }

        if ($registration->checked_in_at) {
            return response()->json([
                'success' => true,
                'already_checked_in' => true,
                'message' => "Already Checked In at " . $registration->checked_in_at->format('H:i:s'),
                'registration' => $registration,
            ]);
        }

        $registration->update([
            'checked_in_at' => now(),
            'checked_in_by' => $request->user()->id,
            'status' => 'attended',
        ]);

        return response()->json([
            'success' => true,
            'already_checked_in' => false,
            'message' => "Successfully Checked In! Welcome {$registration->full_name}.",
            'registration' => $registration,
        ]);
    }
}
