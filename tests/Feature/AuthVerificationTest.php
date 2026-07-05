<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_phone_only_and_be_redirected_to_verification(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+221771234567',
            'country' => 'Senegal',
            'region' => 'Dakar',
            'city' => 'Dakar',
            'quarter' => 'HLM',
            'address' => 'Rue 1',
            'role' => 'vidangeur',
            'verification_channel' => 'phone',
        ]);

        $response->assertRedirect(route('verification.form'));
        $this->assertDatabaseHas('users', [
            'phone' => '+221771234567',
            'role' => 'vidangeur',
        ]);
        $this->assertTrue(User::where('phone', '+221771234567')->first()?->verification_code !== null);
    }
}
