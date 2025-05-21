<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect to mahasiswa dashboard after login
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register request
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'nim' => ['required', 'string', 'max:255', 'unique:mahasiswa'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Mahasiswa record for the new user with default values for required fields
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => 'NIM' . $user->id, // placeholder nim, should be unique
            'prodi' => 'Unknown',       // placeholder prodi
            'angkatan' => date('Y'),    // current year as angkatan
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
