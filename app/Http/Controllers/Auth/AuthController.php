<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
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
            'prodi'    => ['required', 'in:d3,d4'],
            'kelas'    => ['required', function ($attribute, $value, $fail) use ($request) {
                $allowed = [
                    'd3' => ['a', 'b', 'c'],
                    'd4' => ['a', 'b'],
                ];
                $prodi = $request->input('prodi');

                if (!isset($allowed[$prodi]) || !in_array(strtolower($value), $allowed[$prodi])) {
                    $fail('Kelas yang dipilih tidak sesuai dengan program studi.');
                }
            }],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Auto-assign role mahasiswa
        $mahasiswaRole = \App\Models\Role::where('nama_role', 'mahasiswa')->first();
        if ($mahasiswaRole) {
            $user->roles()->attach($mahasiswaRole->id);
        }

        Mahasiswa::create([
            'user_id'  => $user->id,
            'nim'      => $validated['nim'],
            'prodi'    => $validated['prodi'],
            'kelas'    => $validated['kelas'],
            'angkatan' => date('Y'),
        ]);

        $this->sendEmailVerification($user);

        // Store user id in session for OTP verification
        session(['otp_user_id' => $user->id]);

        return redirect()->route('auth.otp.verify.form')->with('success', 'Registrasi berhasil. Silakan masukkan kode OTP yang telah dikirim ke email Anda.');
    }

    // Email verification handler
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

    // Show OTP verification form
    public function showOtpVerificationForm()
    {
        // Check if user id is in session
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi OTP tidak ditemukan. Silakan login kembali.');
        }

        return view('auth.otp_verification');
    }

    // Handle OTP verification
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi OTP tidak ditemukan. Silakan login kembali.');
        }

        $otpCode = $request->input('otp_code');

        $verification = EmailVerificationToken::where('user_id', $userId)
            ->where('token', $otpCode)
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        $user = $verification->user;

        if ($user->email_verified_at) {
            // Already verified, log in user
            Auth::login($user);
            session()->forget('otp_user_id');
            return redirect($this->redirectByRole($user))->with('info', 'Email sudah diverifikasi sebelumnya.');
        }

        $user->email_verified_at = now();
        $user->save();

        $verification->delete();

        // Log in user automatically
        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect($this->redirectByRole($user))->with('success', 'Verifikasi email berhasil. Anda sudah masuk ke dalam sistem.');
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
            return route('dashboard.mahasiswa');
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
                    return route('dashboard.mahasiswa');
            }
        }

        // Default fallback
        return route('dashboard.mahasiswa');
    }

    private function sendEmailVerification(User $user)
    {
        // Generate 6-digit numeric OTP code
        $otpCode = random_int(100000, 999999);

        EmailVerificationToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $otpCode, 'created_at' => now()]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $otpCode));
    }

    // Optional: Assign role to user (currently commented)
    private function assignRole(User $user, string $roleName)
    {
        $role = \App\Models\Role::where('nama_role', $roleName)->first();

        if ($role) {
            $user->roles()->attach($role->id);
        }
    }
}
