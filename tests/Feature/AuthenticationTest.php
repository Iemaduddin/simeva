<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Controllers\AuthenticationController;

class AuthenticationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_login_with_valid_email()
    {
        // Kirim request login
        $response = $this->post('/login', [
            'login' => 'hmti_polinema',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }


    public function test_user_can_register_with_valid_data()
    {
        // Data user dummy untuk registrasi
        $userData = [
            'name' => 'Test User',
            'username' => 'hmti_test2',
            'email' => 'hmti_test2@example.com',
            'phone_number' => '081234567890',
            'address' => 'Malang',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_type' => 'participant',
        ];

        // Kirim POST ke endpoint register
        $response = $this->post('/register', $userData);

        // Cek user masuk ke database
        $this->assertDatabaseHas('users', [
            'email' => 'hmti_test2@example.com',
            'username' => 'hmti_test2',
            'name' => 'Test User',
        ]);

        // Cek user berhasil login setelah register
        $this->assertAuthenticated();

        // Cek redirect ke verifikasi email
        $response->assertRedirect(route('verification.notice'));
    }
    public function test_email_can_be_verified()
    {
        $user = User::factory()->unverified()->create([
            'username' => 'testuser',
        ]);


        $this->actingAs($user);

        $url = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->get($url)->assertRedirect('/');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [ // Pastikan route sesuai
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status'); // Cek status link terkirim

        // Cek token tersimpan di table `password_resets`
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_user_can_reset_password()
    {
        Notification::fake();

        $user = User::factory()->create();

        // Simulasikan pengiriman link reset
        $this->post('/forgot-password', [
            'email' => $user->email,
            'username' => $user->username,
        ]);

        // Ambil token dari notifikasi
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        // Lakukan reset password
        $newPassword = 'newpassword123';
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'username' => $user->username,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect(route('login'));

        // Cek password baru bisa digunakan
        $this->assertTrue(
            Auth::attempt(['email' => $user->email, 'password' => $newPassword])
        );
    }
}
