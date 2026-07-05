<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_page_is_accessible_by_participants()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
        ]);

        Message::create([
            'reservation_id' => $reservation->id,
            'sender_id' => $vidangeur->id,
            'message' => 'Bonjour!',
        ]);

        $response = $this->actingAs($menagere)->get('/chat/' . $reservation->id);
        $response->assertStatus(200);
        $response->assertSee('Bonjour!');
    }

    public function test_chat_page_is_forbidden_for_non_participants()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $other = User::factory()->create(['role' => 'menagere']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
        ]);

        $response = $this->actingAs($other)->get('/chat/' . $reservation->id);
        $response->assertRedirect();
    }

    public function test_user_can_send_message()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
        ]);

        $response = $this->actingAs($menagere)->post('/chat/' . $reservation->id . '/send', [
            'message' => 'Bonjour le vidangeur!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Bonjour le vidangeur!',
            'sender_id' => $menagere->id,
        ]);
    }

    public function test_messages_are_marked_read_when_viewing_chat()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
        ]);

        $msg = Message::create([
            'reservation_id' => $reservation->id,
            'sender_id' => $vidangeur->id,
            'message' => 'Message non lu',
            'is_read' => false,
        ]);

        $this->actingAs($menagere)->get('/chat/' . $reservation->id);

        $this->assertEquals(1, $msg->fresh()->is_read);
    }

    public function test_fetch_returns_messages()
    {
        $menagere = User::factory()->create(['role' => 'menagere']);
        $vidangeur = User::factory()->create(['role' => 'vidangeur']);
        $reservation = Reservation::factory()->create([
            'client_id' => $menagere->id,
            'user_id' => $vidangeur->id,
        ]);

        Message::create([
            'reservation_id' => $reservation->id,
            'sender_id' => $vidangeur->id,
            'message' => 'Message test',
        ]);

        $response = $this->actingAs($menagere)->get('/chat/' . $reservation->id . '/messages');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['message' => 'Message test']);
    }
}
