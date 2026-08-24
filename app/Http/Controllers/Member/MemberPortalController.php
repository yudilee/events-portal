<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberPortalController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user = $request->user();

        $activeTickets = Registration::with(['event.businessUnit'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->whereHas('event', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        $exclusiveEvents = Event::with('businessUnit')
            ->published()
            ->upcoming()
            ->take(4)
            ->get();

        return Inertia::render('Member/Dashboard', [
            'activeTickets' => $activeTickets,
            'exclusiveEvents' => $exclusiveEvents,
        ]);
    }

    public function tickets(Request $request): Response
    {
        $user = $request->user();

        $tickets = Registration::with(['event.businessUnit'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->latest()
            ->paginate(10);

        return Inertia::render('Member/Tickets', [
            'tickets' => $tickets,
        ]);
    }
}
