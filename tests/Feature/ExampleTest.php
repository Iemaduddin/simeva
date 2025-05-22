<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AuthenticationController;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_login_with_valid_email()
    {
        // Buat user dummy di database
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => bcrypt('password123'), // Simulasi password yang benar
        ]);

        // Buat request manual
        $request = Request::create('/login', 'POST', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Mulai session agar bisa regenerate
        // Session::start();

        // Simulasikan login
        $controller = new AuthenticationController();
        $response = $controller->login($request);

        // Assert redirect (login sukses)
        $this->assertEquals(302, $response->status());
        $this->assertTrue(Auth::check());
    }

    // public function test_login_with_invalid_credentials()
    // {
    //     // Request dengan data salah
    //     $request = Request::create('/login', 'POST', [
    //         'login' => 'wrong@example.com',
    //         'password' => 'wrongpassword',
    //     ]);

    //     // Harus mulai session untuk test
    //     Session::start();

    //     $controller = new AuthenticationController();
    //     $response = $controller->login($request);

    //     // Harus redirect back dengan error
    //     $this->assertEquals(302, $response->status());
    //     $this->assertFalse(Auth::check());
    // }
}