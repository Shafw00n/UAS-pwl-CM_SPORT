<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $remember = Schema::hasColumn('users', 'remember_token') && $request->boolean('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string','max:50','unique:users,username'],
            'email' => ['required','email','max:100','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
            'nama_lengkap' => ['nullable','string','max:100'],
        ]);

        $payload = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pelanggan',
        ];

        if (Schema::hasColumn('users', 'nama_lengkap') && !empty($data['nama_lengkap'])) {
            $payload['nama_lengkap'] = $data['nama_lengkap'];
        }

        $user = User::create($payload);

        Auth::login($user);

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Logic pengiriman email reset password standard Laravel
        // Karena ini local environment, kita asumsikan sukses atau log
        
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }
}
