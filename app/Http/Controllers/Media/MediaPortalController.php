<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\PressRelease;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MediaPortalController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user = $request->user();

        $pressReleases = PressRelease::with(['businessUnit', 'event'])
            ->latest('created_at')
            ->take(6)
            ->get();

        $myAccreditations = Registration::with('event')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->where('attendee_type', 'media')
            ->latest()
            ->get();

        $upcomingEvents = Event::with('businessUnit')
            ->published()
            ->upcoming()
            ->take(3)
            ->get();

        return Inertia::render('Media/Dashboard', [
            'pressReleases' => $pressReleases,
            'myAccreditations' => $myAccreditations,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function pressReleases(): Response
    {
        $releases = PressRelease::with(['businessUnit', 'event'])
            ->latest('created_at')
            ->paginate(12);

        $businessUnits = BusinessUnit::all();

        return Inertia::render('Media/PressReleases', [
            'releases' => $releases,
            'businessUnits' => $businessUnits,
        ]);
    }

    public function requestAccreditation(Request $request, int $eventId): RedirectResponse
    {
        $event = Event::findOrFail($eventId);
        $user = $request->user();

        $existing = Registration::where('event_id', $event->id)
            ->where('email', $user->email)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied or registered for this event.');
        }

        Registration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '+62 811-000-000',
            'company' => $user->company ?? 'Media Editorial',
            'attendee_type' => 'media',
            'media_outlet_name' => $user->company ?? 'Media Outlet',
            'num_attendees' => 2,
            'status' => 'confirmed',
            'notes' => 'Official Media Press Pass Request',
        ]);

        return back()->with('success', 'Media Accreditation approved! Your Press Pass is now active.');
    }
}
