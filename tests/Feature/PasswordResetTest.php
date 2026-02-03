<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_request_password_reset_link()
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post(route('password.email'), [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $user,
            \Illuminate\Auth\Notifications\ResetPassword::class
        );
    }

    #[Test]
    public function user_cannot_request_reset_with_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    }

    #[Test]
    public function user_can_reset_password_with_valid_token()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);
        $token = Password::createToken($user);

        // Act
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect('/home'); 
        
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    #[Test]
    public function user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }
    
    #[Test]
    public function password_must_be_confirmed()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        // ✅ PERBAIKAN: Gunakan route('password.update') bukan hardcode
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    #[Test]
    public function password_must_meet_minimum_length()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        // ✅ PERBAIKAN: Gunakan route('password.update') bukan hardcode
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    #[Test]
    public function token_expires_after_set_time()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        // Simulasi waktu berlalu (61 menit)
        $this->travel(61)->minutes();

        // ✅ PERBAIKAN: Gunakan route('password.update') bukan hardcode
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function multiple_users_can_reset_password_independently()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        Notification::fake();

        $this->post(route('password.email'), ['email' => 'user1@example.com']);
        $this->post(route('password.email'), ['email' => 'user2@example.com']);

        $token1 = Password::createToken($user1);
        $token2 = Password::createToken($user2);

        $this->post(route('password.update'), [
            'token' => $token1,
            'email' => 'user1@example.com',
            'password' => 'newpass1',
            'password_confirmation' => 'newpass1',
        ]);

        $this->post(route('password.update'), [
            'token' => $token2,
            'email' => 'user2@example.com',
            'password' => 'newpass2',
            'password_confirmation' => 'newpass2',
        ]);

        $user1->refresh();
        $user2->refresh();
        
        $this->assertTrue(Hash::check('newpass1', $user1->password));
        $this->assertTrue(Hash::check('newpass2', $user2->password));
    }
}