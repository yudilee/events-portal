<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doorprize;
use App\Models\DoorprizeWinner;
use App\Models\Event;
use App\Models\GuestBookEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoorprizeController extends Controller
{
    public function index(Request $request, ?int $eventId = null): Response
    {
        $events = Event::orderBy('date', 'desc')->get();
        $selectedEvent = $eventId ? Event::findOrFail($eventId) : $events->first();

        $prizes = [];
        $poolStats = [
            'total_guestbook_entries' => 0,
            'total_prizes' => 0,
            'awarded_prizes' => 0,
            'remaining_pool' => 0,
        ];

        if ($selectedEvent) {
            $prizes = Doorprize::with(['winners.guestBookEntry', 'winnerEntry'])
                ->where('event_id', $selectedEvent->id)
                ->orderBy('sort_order', 'asc')
                ->get();

            $totalEntries = GuestBookEntry::where('event_id', $selectedEvent->id)
                ->where('is_approved', true)
                ->count();

            $wonEntryIds = DoorprizeWinner::whereHas('doorprize', function ($q) use ($selectedEvent) {
                $q->where('event_id', $selectedEvent->id);
            })->pluck('guest_book_entry_id')->unique();

            // Total prize units available (sum of quantity across all prize tiers)
            $totalPrizesCount = $prizes->sum(fn ($p) => $p->quantity ?: 1);
            $awardedPrizesCount = $wonEntryIds->count();

            $poolStats = [
                'total_guestbook_entries' => $totalEntries,
                'total_prizes' => $totalPrizesCount,
                'awarded_prizes' => $awardedPrizesCount,
                'remaining_pool' => max(0, $totalEntries - $awardedPrizesCount),
            ];
        }

        return Inertia::render('Admin/Doorprize/Setup', [
            'events' => $events,
            'selectedEvent' => $selectedEvent,
            'prizes' => $prizes,
            'poolStats' => $poolStats,
        ]);
    }

    public function store(Request $request, int $eventId): RedirectResponse
    {
        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $maxOrder = Doorprize::where('event_id', $event->id)->max('sort_order') ?? 0;

        Doorprize::create([
            'event_id' => $event->id,
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null,
            'quantity' => $validated['quantity'] ?? 1,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', "Prize '{$validated['label']}' ({$validated['quantity']} winner quota) added successfully.");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $prize = Doorprize::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $prize->update($validated);

        return back()->with('success', "Prize '{$prize->label}' updated successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $prize = Doorprize::findOrFail($id);
        $prizeName = $prize->label;
        $prize->delete();

        return back()->with('success', "Prize '{$prizeName}' deleted.");
    }

    public function reorder(Request $request, int $eventId): JsonResponse
    {
        $validated = $request->validate([
            'prize_ids' => 'required|array',
            'prize_ids.*' => 'integer|exists:doorprizes,id',
        ]);

        foreach ($validated['prize_ids'] as $index => $prizeId) {
            Doorprize::where('id', $prizeId)
                ->where('event_id', $eventId)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function roulette(int $eventId): Response
    {
        $event = Event::with('businessUnit')->findOrFail($eventId);

        $prizes = Doorprize::with(['winners.guestBookEntry', 'winnerEntry'])
            ->where('event_id', $event->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get all approved guestbook entries for this event
        $guestbookEntries = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->select(['id', 'guest_name', 'company', 'message', 'created_at'])
            ->latest()
            ->get();

        // Already won entry IDs across all doorprizes in this event
        $wonEntryIds = DoorprizeWinner::whereHas('doorprize', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->pluck('guest_book_entry_id')->unique()->values()->toArray();

        return Inertia::render('Admin/Doorprize/Roulette', [
            'event' => $event,
            'prizes' => $prizes,
            'guestbookEntries' => $guestbookEntries,
            'wonEntryIds' => $wonEntryIds,
        ]);
    }

    public function spin(Request $request, int $eventId): JsonResponse
    {
        $event = Event::findOrFail($eventId);

        // Targeted prize or next unwon prize
        $targetPrizeId = $request->input('prize_id');
        if ($targetPrizeId) {
            $prize = Doorprize::with('winners.guestBookEntry')
                ->where('event_id', $event->id)
                ->where('id', $targetPrizeId)
                ->first();

            if ($prize && $prize->winners->count() >= ($prize->quantity ?: 1)) {
                return response()->json([
                    'success' => false,
                    'message' => "All {$prize->quantity} winner slots for '{$prize->label}' have already been awarded! Select another prize or increase the winner quota.",
                ], 422);
            }
        } else {
            // Find first prize that has remaining winner slots
            $allPrizes = Doorprize::with('winners.guestBookEntry')
                ->where('event_id', $event->id)
                ->orderBy('sort_order', 'asc')
                ->get();

            $prize = $allPrizes->first(function ($p) {
                return $p->winners->count() < ($p->quantity ?: 1);
            });
        }

        if (!$prize) {
            return response()->json([
                'success' => false,
                'message' => 'All pre-defined doorprizes have already been awarded! Add more prizes or increase winner quotas in setup.',
            ], 422);
        }

        // Exclude all previously won entry IDs in this event across all prizes
        $wonEntryIds = DoorprizeWinner::whereHas('doorprize', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->pluck('guest_book_entry_id')->unique()->values()->toArray();

        $eligibleWinner = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->whereNotIn('id', $wonEntryIds)
            ->inRandomOrder()
            ->first();

        if (!$eligibleWinner) {
            return response()->json([
                'success' => false,
                'message' => 'No eligible guestbook entries remaining in the pool. Guests must sign the digital guestbook to be entered into the draw.',
            ], 422);
        }

        // Record winner in doorprize_winners table
        $winnerRecord = DoorprizeWinner::create([
            'doorprize_id' => $prize->id,
            'guest_book_entry_id' => $eligibleWinner->id,
            'won_at' => now(),
        ]);

        // Update legacy columns on doorprizes for backward compatibility
        $prize->update([
            'winner_guest_book_entry_id' => $eligibleWinner->id,
            'won_at' => now(),
        ]);

        $prize->load('winners.guestBookEntry');

        $remainingCount = GuestBookEntry::where('event_id', $event->id)
            ->where('is_approved', true)
            ->whereNotIn('id', array_merge($wonEntryIds, [$eligibleWinner->id]))
            ->count();

        $currentWinnerIndex = $prize->winners->count();
        $totalQuota = $prize->quantity ?: 1;

        return response()->json([
            'success' => true,
            'winner' => $eligibleWinner,
            'prize' => $prize,
            'winner_index' => $currentWinnerIndex,
            'total_quota' => $totalQuota,
            'is_fully_awarded' => $currentWinnerIndex >= $totalQuota,
            'remaining_count' => $remainingCount,
        ]);
    }

    public function resetPrize(int $id): RedirectResponse
    {
        $prize = Doorprize::findOrFail($id);

        DoorprizeWinner::where('doorprize_id', $prize->id)->delete();

        $prize->update([
            'winner_guest_book_entry_id' => null,
            'won_at' => null,
        ]);

        return back()->with('success', "All winners for prize '{$prize->label}' reset. Guests returned to draw pool.");
    }

    public function deleteWinner(int $winnerId): RedirectResponse
    {
        $winner = DoorprizeWinner::with('doorprize')->findOrFail($winnerId);
        $prize = $winner->doorprize;
        $winner->delete();

        // Update legacy field on prize if needed
        $latestRemaining = DoorprizeWinner::where('doorprize_id', $prize->id)->latest()->first();
        $prize->update([
            'winner_guest_book_entry_id' => $latestRemaining?->guest_book_entry_id,
            'won_at' => $latestRemaining?->won_at,
        ]);

        return back()->with('success', 'Winner removed from prize. Guest returned to draw pool.');
    }

    public function resetAll(int $eventId): RedirectResponse
    {
        $prizeIds = Doorprize::where('event_id', $eventId)->pluck('id');

        DoorprizeWinner::whereIn('doorprize_id', $prizeIds)->delete();

        Doorprize::where('event_id', $eventId)->update([
            'winner_guest_book_entry_id' => null,
            'won_at' => null,
        ]);

        return back()->with('success', 'All doorprize winners for this event have been reset.');
    }
}
