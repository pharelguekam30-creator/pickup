<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_menagere_can_create_reservation()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $service = Service::factory()->create(['price' => 10000]);

        $response = $this->actingAs($menagere)->post('/reservations', [
            'service_id' => $service->id,
            'vidangeur_id' => $vidangeur->id,
            'reservation_date' => now()->addDay()->format('Y-m-d H:i'),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reservations', [
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
            'service_id' => $service->id,
            'status' => 'pending',
        ]);
    }

    public function test_vidangeur_can_accept_reservation()
    {
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'user_id' => $vidangeur->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($vidangeur)->post('/reservations/' . $reservation->id . '/accept');

        $response->assertSessionHasNoErrors();
        $this->assertEquals('accepted', $reservation->fresh()->status);
    }

    public function test_vidangeur_can_mark_complete()
    {
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'user_id' => $vidangeur->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($vidangeur)->post('/reservations/' . $reservation->id . '/complete');

        $response->assertSessionHasNoErrors();
        $this->assertEquals('completed_vidangeur', $reservation->fresh()->status);
    }

    public function test_menagere_can_confirm_and_triggers_payment()
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

        $response = $this->actingAs($menagere)->post('/reservations/' . $reservation->id . '/confirm');

        $response->assertSessionHasNoErrors();

        $reservation->refresh();
        $this->assertEquals('completed', $reservation->status);

        $vidangeur->refresh();
        $this->assertEquals(6500, $vidangeur->solde);

        $admin->refresh();
        $this->assertEquals(3500, $admin->solde);

        $this->assertDatabaseHas('transactions', [
            'reservation_id' => $reservation->id,
            'type' => 'paiement',
            'montant' => 6500,
        ]);

        $this->assertDatabaseHas('transactions', [
            'reservation_id' => $reservation->id,
            'type' => 'commission',
            'montant' => 3500,
        ]);
    }

    public function test_vidangeur_cannot_accept_already_accepted_reservation()
    {
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'user_id' => $vidangeur->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($vidangeur)->post('/reservations/' . $reservation->id . '/accept');

        $response->assertSessionHasErrors();
    }

    public function test_menagere_cannot_confirm_others_reservation()
    {
        $menagere1 = User::factory()->create(['role' => 'menagere']);
        $menagere2 = User::factory()->create(['role' => 'menagere']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere1->id,
            'status' => 'completed_vidangeur',
        ]);

        $response = $this->actingAs($menagere2)->post('/reservations/' . $reservation->id . '/confirm');

        $response->assertSessionHasErrors();
    }

    public function test_vidangeur_can_cancel_pending_reservation()
    {
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'user_id' => $vidangeur->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($vidangeur)->post('/reservations/' . $reservation->id . '/cancel');

        $response->assertSessionHasNoErrors();
        $this->assertEquals('canceled', $reservation->fresh()->status);
    }
}
