<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\Doorprize;
use App\Models\Event;
use App\Models\GuestBookEntry;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DoorprizeTest extends TestCase
{
    use WithoutMiddleware;
    private function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@hartonomotor-group.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);
    }

    private function getTestEvent(): Event
    {
        $event = Event::first();
        if (!$event) {
            $bu = BusinessUnit::first() ?? BusinessUnit::create([
                'name' => 'Hartono Motor Test',
                'slug' => 'hartono-motor-test',
                'description' => 'Test Unit',
            ]);

            $event = Event::create([
                'business_unit_id' => $bu->id,
                'title' => 'Mercedes-Benz Test Gala 2026',
                'slug' => 'mercedes-benz-test-gala-2026',
                'event_type' => 'customer_gathering',
                'date' => '2026-09-01',
                'start_time' => '10:00:00',
                'end_time' => '15:00:00',
                'timezone' => 'WIB',
                'venue_name' => 'Grand Ballroom',
                'venue_address' => 'Surabaya',
                'max_capacity' => 200,
                'status' => 'published',
                'is_registration_enabled' => true,
                'is_guestbook_enabled' => true,
            ]);
        }
        return $event;
    }

    public function test_multi_attendee_registration_creates_individual_tickets(): void
    {
        $event = $this->getTestEvent();

        $response = $this->post(route('public.events.register.store', $event->slug), [
            'full_name' => 'Ir. Hartono Primary',
            'email' => 'hartono@example.com',
            'phone' => '+62812345678',
            'company' => 'PT Mitra Sejati',
            'attendee_type' => 'vip',
            'num_attendees' => 3,
            'companions' => [
                ['full_name' => 'Budi Santoso (Colleague 1)'],
                ['full_name' => 'Siti Rahma (Colleague 2)'],
            ],
        ]);

        $response->assertRedirect();

        // Primary registration exists
        $primary = Registration::where('email', 'hartono@example.com')->whereNull('parent_registration_id')->first();
        $this->assertNotNull($primary);
        $this->assertEquals('Ir. Hartono Primary', $primary->full_name);

        // Companions exist and link to primary
        $companions = Registration::where('parent_registration_id', $primary->id)->get();
        $this->assertCount(2, $companions);
        $this->assertEquals('Budi Santoso (Colleague 1)', $companions[0]->full_name);
        $this->assertEquals('Siti Rahma (Colleague 2)', $companions[1]->full_name);
        $this->assertNotEquals($primary->registration_code, $companions[0]->registration_code);
    }

    public function test_admin_can_access_doorprize_setup(): void
    {
        $admin = $this->getAdminUser();
        $event = $this->getTestEvent();

        $response = $this->actingAs($admin)->get(route('admin.events.doorprize', $event->id));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Doorprize/Setup')
            ->has('events')
            ->has('selectedEvent')
            ->has('prizes')
            ->has('poolStats')
        );
    }

    public function test_admin_can_create_prize_tier(): void
    {
        $admin = $this->getAdminUser();
        $event = $this->getTestEvent();

        $response = $this->actingAs($admin)->post(route('admin.events.doorprize.store', $event->id), [
            'label' => 'Grand Prize Test',
            'description' => 'Mercedes-Benz Chronograph Watch',
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('doorprizes', [
            'event_id' => $event->id,
            'label' => 'Grand Prize Test',
        ]);
    }

    public function test_roulette_spin_selects_winner_from_guestbook(): void
    {
        $admin = $this->getAdminUser();
        $event = $this->getTestEvent();

        // Create a test guestbook entry
        $entry1 = GuestBookEntry::create([
            'event_id' => $event->id,
            'guest_name' => 'Doorprize Winner Alpha',
            'company' => 'PT Alpha',
            'message' => 'Good luck for the opening!',
            'is_approved' => true,
        ]);

        $prize = Doorprize::create([
            'event_id' => $event->id,
            'label' => 'Roulette Spin Test Prize',
            'description' => 'Voucher Rp 5.000.000',
            'sort_order' => 999,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.events.doorprize.spin', $event->id), [
            'prize_id' => $prize->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'winner',
            'prize',
            'remaining_count',
        ]);

        $this->assertDatabaseHas('doorprizes', [
            'id' => $prize->id,
            'winner_guest_book_entry_id' => $response->json('winner.id'),
        ]);
    }
}
