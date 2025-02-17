<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthenticationController extends Controller
{

    public function showRegisterPage()
    {
        return view('authentication/register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|max:255|unique:users',
            'no_hp' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role_type' => 'required|string|in:participant,tenant',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter!',

            'username.required' => 'Username harus diisi!',
            'username.unique' => 'Username sudah digunakan!',
            'username.max' => 'Username tidak boleh lebih dari 255 karakter!',

            'email.required' => 'Email harus diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter!',

            'address.max' => 'Alamat tidak boleh lebih dari 255 karakter!',

            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password harus minimal 8 karakter!',

            'role_type.required' => 'Role harus diisi!',
            'role_type.in' => 'Role harus berupa participant atau tenant!',
        ]);


        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp ?? '',
            'address' => $request->address ?? '',
            'password' => Hash::make($request->password),
            'category_user' => 'external'
        ]);

        $request->role_type == 'participant' ?
            $user->assignRole('participant') : $user->assignRole('tenant');

        Auth::login($user);

        event(new Registered($user)); //  Kirim Email Verifikasi

        return redirect()->route('verification.notice')
            ->with('success', 'Silakan cek email Anda untuk verifikasi.');
    }
    public function showLoginPage()
    {
        return view('authentication/login');
    }
    public function login(Request $request)
    {

        $credentials = $request->validate(
            [
                'login' => ['required'],
                'password' => ['required'],
            ],
            [
                'login.required' => 'Username/Email harus diisi!',
                'password.required' => 'Password harus diisi!',
            ]
        );

        if (
            Auth::attempt(['email' => $credentials['login'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['login'], 'password' => $credentials['password']])
        ) {
            $request->session()->regenerate();
            if ($request->has('rememberMe')) {
                Cookie::queue('userUsername', $request->input('login'), 1440);
            }
            // Cek peran pengguna dan arahkan ke halaman yang sesuai

            $user = Auth::user();

            if ($user->hasRole('Super Admin')) {
                // Arahkan ke halaman dashboard untuk Super Admin
                return redirect()->route('stakeholderUsers');
            } elseif ($user->hasRole('Participant')) {
                // Arahkan ke halaman home untuk Participant
                return redirect()->route('home');
            }

            // Jika peran tidak cocok, arahkan ke halaman default atau berikan error
            return redirect()->route('home');
        }
        return back()->withErrors([
            'username' => 'Username atau Password Anda Salah!',
        ])->withInput();
    }

    public function forgotPassword()
    {
        return view('authentication.forgot-password');
    }
    public function requestResetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPasswordPage(string $token)
    {
        return view('authentication.reset-password', ['token' => $token]);
    }
    public function resetPassword(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
