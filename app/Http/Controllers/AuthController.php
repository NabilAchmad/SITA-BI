<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\EmailVerificationToken;
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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectByRole(Auth::user()));
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

    // Handle registration request
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'nim'      => ['required', 'string', 'max:255', 'unique:mahasiswa'],
            'prodi'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Optional: assign "mahasiswa" role (commented)
        // $this->assignRole($user, 'mahasiswa');

        Mahasiswa::create([
            'user_id'  => $user->id,
            'nim'      => $validated['nim'],
            'prodi'    => $validated['prodi'],
            'angkatan' => date('Y'),
        ]);

        $this->sendOtpVerification($user);

        return redirect()->route('auth.otp.form')->with('success', 'Registrasi berhasil. Silakan periksa email Anda untuk kode OTP verifikasi.');
    }

    // Show OTP input form
    public function showOtpForm()
    {
        return view('auth.otp');
    }

    // Handle OTP verification and login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $otpRecord = EmailVerificationToken::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Check OTP expiration (10 minutes)
        $created = $otpRecord->created_at;
        if (now()->diffInMinutes($created) > 10) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }

        // Mark email as verified
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Delete OTP record
        $otpRecord->delete();

        // Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectByRole($user));
    }

    // Email verification handler (deprecated, kept for backward compatibility)
    public function verifyEmail($token)
    {
        $verification = EmailVerificationToken::where('token', $token)->first();

        if (!$verification) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $user = $verification->user;

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('info', 'Email sudah diverifikasi sebelumnya.');
        }

        $user->email_verified_at = now();
        $user->save();

        $verification->delete();

        $redirectTo = Session::pull('url.intended', route('login'));
        return redirect($redirectTo)->with('success', 'Verifikasi email berhasil.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ===========================
    // Private helper methods
    // ===========================

    private function redirectByRole($user)
    {
        if (!$user->roles || $user->roles->isEmpty()) {
            return route('mahasiswa.dashboard');
        }

        foreach ($user->roles as $role) {
            switch ($role->nama_role) {
                case 'admin':
                    return route('admin.dashboard');
                case 'kajur':
                    return route('kajur.dashboard');
                case 'kaprodi':
                    return route('kaprodi.dashboard');
                case 'dosen':
                    return route('dosen.dashboard');
                case 'mahasiswa':
                    return route('mahasiswa.dashboard');
            }
        }

        // Default fallback
        return route('mahasiswa.dashboard');
    }

    private function sendEmailVerification(User $user)
    {
        $token = Str::random(64);

        EmailVerificationToken::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $token));
    }

    private function sendOtpVerification(User $user)
    {
        $otp = random_int(100000, 999999);

        EmailVerificationToken::create([
            'user_id'    => $user->id,
            'token'      => $otp,
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new \App\Mail\OtpVerification($user, $otp));
    }

    // Optional: Assign role to user (currently commented)
    // private function assignRole(User $user, string $roleName)
    // {
    //     $role = \App\Models\Role::where('nama_role', $roleName)->first();
    //     if ($role) {
    //         $user->roles()->attach($role->id);
    //     }
    // }
}

// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
// use App\Models\Mahasiswa;
// use App\Models\EmailVerificationToken;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\VerifyEmail;

// class AuthController extends Controller
// {
//     // Show login form
//     public function showLogin()
//     {
//         return view('auth.login');
//     }

//     // Handle login request
//     public function login(Request $request)
//     {
//         $credentials = $request->validate([
//             'email' => ['required', 'email'],
//             'password' => ['required'],
//         ]);

//         if (Auth::attempt($credentials)) {
//             $request->session()->regenerate();

//             $user = Auth::user();

//             // Check user roles and redirect accordingly
//             if ($user->roles && $user->roles->count() > 0) {
//                 if ($user->roles->contains('nama_role', 'admin')) {
//                     return redirect()->intended(route('admin.dashboard'));
//                 } elseif ($user->roles->contains('nama_role', 'kajur')) {
//                     return redirect()->intended(route('kajur.dashboard'));
//                 } elseif ($user->roles->contains('nama_role', 'kaprodi')) {
//                     return redirect()->intended(route('kaprodi.dashboard'));
//                 } elseif ($user->roles->contains('nama_role', 'dosen')) {
//                     // Assuming dosen dashboard route exists
//                     return redirect()->intended(route('dosen.dashboard'));
//                 } elseif ($user->roles->contains('nama_role', 'mahasiswa')) {
//                     return redirect()->intended(route('mahasiswa.dashboard'));
//                 } else {
//                     // Default fallback
//                     return redirect()->intended(route('mahasiswa.dashboard'));
//                 }
//             } else {
//                 // If no roles exist, fallback to mahasiswa dashboard
//                 return redirect()->intended(route('mahasiswa.dashboard'));
//             }
//         }

//         throw ValidationException::withMessages([
//             'email' => __('auth.failed'),
//         ]);
//     }
//     // Show register form
//     public function showRegister()
//     {
//         return view('auth.register');
//     }

//     // Handle register request
//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'email', 'max:255', 'unique:users'],
//             'nim' => ['required', 'string', 'max:255', 'unique:mahasiswa'],
//             'prodi' => ['required', 'string', 'max:255'],
//             'password' => ['required', 'confirmed', 'min:8'],
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         // Assign "mahasiswa" role to the new user
//         // $studentRole = \App\Models\Role::where('nama_role', 'mahasiswa')->first();
//         // if ($studentRole) {
//         //     $user->roles()->attach($studentRole->id);
//         // }

//         // Create Mahasiswa record for the new user with provided values
//         Mahasiswa::create([
//             'user_id' => $user->id,
//             'nim' => $request->nim,
//             'prodi' => $request->prodi,
//             'angkatan' => date('Y'),    // current year as angkatan
//         ]);

//         // Generate email verification token
//         $token = Str::random(64);

//         EmailVerificationToken::create([
//             'user_id' => $user->id,
//             'token' => $token,
//             'created_at' => now(),
//         ]);

//         // Send verification email
//         Mail::to($user->email)->send(new VerifyEmail($user, $token));

//         // Do not log in user until email is verified
//         return redirect()->route('login')->with('success', 'Registration successful. Please check your email to verify your account.');
//     }

//     // Verify email
//     public function verifyEmail($token)
//     {
//         $verification = EmailVerificationToken::where('token', $token)->first();

//         if (!$verification) {
//             return redirect()->route('login')->with('error', 'Invalid or expired verification token.');
//         }

//         $user = $verification->user;

//         if ($user->email_verified_at) {
//             return redirect()->route('login')->with('info', 'Email already verified.');
//         }

//         $user->email_verified_at = now();
//         $user->save();

//         // Delete the token after verification
//         $verification->delete();

//         $redirectTo = session()->pull('url.intended', route('login'));
//         return redirect($redirectTo)->with('success', 'Email verified successfully.');
//     }

//     // Handle logout
//     public function logout(Request $request)
//     {
//         Auth::logout();

//         $request->session()->invalidate();

//         $request->session()->regenerateToken();

//         return redirect('/');
//     }
// }