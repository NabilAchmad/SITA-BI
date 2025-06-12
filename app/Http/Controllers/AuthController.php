<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Mahasiswa;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

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

            $user = Auth::user();

            // Check user roles and redirect accordingly
            if ($user->roles && $user->roles->count() > 0) {
                if ($user->roles->contains('nama_role', 'admin')) {
                    return redirect()->intended(route('admin.dashboard'));
                } elseif ($user->roles->contains('nama_role', 'kajur')) {
                    return redirect()->intended(route('kajur.dashboard'));
                } elseif ($user->roles->contains('nama_role', 'kaprodi')) {
                    return redirect()->intended(route('kaprodi.dashboard'));
                } elseif ($user->roles->contains('nama_role', 'dosen')) {
                    // Assuming dosen dashboard route exists
                    return redirect()->intended(route('dosen.dashboard'));
                } elseif ($user->roles->contains('nama_role', 'mahasiswa')) {
                    return redirect()->intended(route('mahasiswa.dashboard'));
                } else {
                    // Default fallback
                    return redirect()->intended(route('mahasiswa.dashboard'));
                }
            } else {
                // If no roles exist, fallback to mahasiswa dashboard
                return redirect()->intended(route('mahasiswa.dashboard'));
            }
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
            'prodi' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign "mahasiswa" role to the new user
        // $studentRole = \App\Models\Role::where('nama_role', 'mahasiswa')->first();
        // if ($studentRole) {
        //     $user->roles()->attach($studentRole->id);
        // }

        // Create Mahasiswa record for the new user with provided values
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'angkatan' => date('Y'),    // current year as angkatan
        ]);

        // Generate email verification token
        $token = Str::random(64);

        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send verification email
        Mail::to($user->email)->send(new VerifyEmail($user, $token));

        // Do not log in user until email is verified
        return redirect()->route('login')->with('success', 'Registration successful. Please check your email to verify your account.');
    }

    // Verify email
    public function verifyEmail($token)
    {
        $verification = EmailVerificationToken::where('token', $token)->first();

        if (!$verification) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification token.');
        }

        $user = $verification->user;

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('info', 'Email already verified.');
        }

        $user->email_verified_at = now();
        $user->save();

        // Delete the token after verification
        $verification->delete();

        $redirectTo = session()->pull('url.intended', route('login'));
        return redirect($redirectTo)->with('success', 'Email verified successfully.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
