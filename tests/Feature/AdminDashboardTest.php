<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard')
            ->has('metrics')
            ->has('upcomingEvents')
            ->has('recentRegistrations')
        );
    }

    public function test_admin_can_access_business_units(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get('/admin/business-units');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/BusinessUnits/Index')
            ->has('businessUnits')
        );
    }

    public function test_admin_can_access_banners(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get('/admin/banners');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Banners/Index')
            ->has('slides')
        );
    }
}
