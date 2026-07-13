<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_force_complete_reservation()
    {
        $admin = User::factory()->create(['role' => 'admin', 'solde' => 0]);
        $vidangeur = User::factory()->create(['role' => 'vidangeur', 'solde' => 0]);
        $menagere = User::factory()->create(['role' => 'menagere', 'solde' => 50000]);
        $service = Service::factory()->create(['price' => 10000]);

        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
            'service_id' => $service->id,
            'status' => 'completed_vidangeur',
        ]);

        $response = $this->actingAs($admin)->post('/admin/reservations/' . $reservation->id . '/force-complete');

        $response->assertSessionHasNoErrors();
        $this->assertEquals('completed', $reservation->fresh()->status);
        $this->assertEquals(6500, $vidangeur->fresh()->solde);
    }

    public function test_admin_can_force_cancel_reservation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reservation = Reservation::factory()->create([
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($admin)->post('/admin/reservations/' . $reservation->id . '/force-cancel');

        $response->assertSessionHasNoErrors();
        $this->assertEquals('canceled', $reservation->fresh()->status);
    }

    public function test_admin_can_access_stats()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/stats');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);

        $response = $this->actingAs($menagere)->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_force_complete()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($menagere)->post('/admin/reservations/' . $reservation->id . '/force-complete');
        $response->assertStatus(403);
    }
}
