<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GuestBookEntry;
use App\Models\Registration;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalEvents = Event::count();
        $totalRegistrations = Registration::count();
        $totalCheckedIn = Registration::whereNotNull('checked_in_at')->count();
        $totalGuestbook = GuestBookEntry::count();

        $recentRegistrations = Registration::with('event')
            ->latest()
            ->take(8)
            ->get();

        $upcomingEvents = Event::with('businessUnit')
            ->withCount(['registrations as attendees_count' => function ($q) {
                $q->whereIn('status', ['confirmed', 'attended']);
            }])
            ->withCount(['registrations as checked_in_count' => function ($q) {
                $q->whereNotNull('checked_in_at');
            }])
            ->upcoming()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'totalEvents' => $totalEvents,
                'totalRegistrations' => $totalRegistrations,
                'totalCheckedIn' => $totalCheckedIn,
                'totalGuestbook' => $totalGuestbook,
            ],
            'recentRegistrations' => $recentRegistrations,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
