<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_form_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register_as_menagere()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Marie',
            'email' => 'marie@test.com',
            'phone' => '690000001',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'menagere',
            'city' => 'Douala',
            'quarter' => 'Bonanjo',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'marie@test.com',
            'role' => 'menagere',
            'city' => 'Douala',
            'quarter' => 'Bonanjo',
        ]);
    }

    public function test_user_can_register_as_vidangeur()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Paul',
            'email' => 'paul@test.com',
            'phone' => '690000002',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'vidangeur',
            'city' => 'Yaounde',
            'quarter' => 'Mfoundi',
            'tarif' => '5000',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'paul@test.com',
            'role' => 'vidangeur',
        ]);

        $user = User::where('email', 'paul@test.com')->first();
        $this->assertNotNull($user->latitude);
        $this->assertNotNull($user->longitude);
        $this->assertNotNull($user->verification_code);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'menagere',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
    }

    public function test_login_with_invalid_credentials_fails()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_verification_with_valid_code()
    {
        $user = User::factory()->create([
            'verification_code' => '123456',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/verification', [
            'code' => '123456',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_verification_with_invalid_code_fails()
    {
        $user = User::factory()->create([
            'verification_code' => '123456',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/verification', [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors();
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/logout');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_profile()
    {
        $user = User::factory()->create(['role' => 'menagere']);
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_profile()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }
}
