<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\EmailVerificationToken;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Ditambahkan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
// Tidak perlu `use Spatie\Permission\Models\Role;` karena kita bisa memanggilnya langsung

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role setelah login berhasil
            return redirect()->intended($this->redirectByRole(Auth::user()));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Menampilkan form registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses registrasi.
     * SUDAH DIPERBAIKI: Menggunakan DB Transaction dan assignRole().
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'nim'      => ['required', 'string', 'max:10', 'unique:mahasiswa'],
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

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Memberikan role 'mahasiswa' menggunakan metode dari Spatie
            $user->assignRole('mahasiswa');

            Mahasiswa::create([
                'user_id'  => $user->id,
                'nim'      => $validated['nim'],
                'prodi'    => $validated['prodi'],
                'kelas'    => $validated['kelas'],
                'angkatan' => 2000 + intval(substr($validated['nim'], 0, 2)),
            ]);

            // Jika semua berhasil, simpan perubahan
            DB::commit();
        } catch (\Exception $e) {
            // Jika ada error, batalkan semua operasi
            DB::rollBack();
            // Tampilkan pesan error
            return back()->with('error', 'Registrasi gagal, terjadi kesalahan pada server. Silakan coba lagi.')->withInput();
        }

        $this->sendEmailVerification($user);

        session(['otp_user_id' => $user->id]);

        return redirect()->route('auth.otp.verify.form')->with('success', 'Registrasi berhasil. Silakan masukkan kode OTP yang telah dikirim ke email Anda.');
    }

    /**
     * Menangani proses verifikasi OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => ['required', 'digits:6']]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi OTP tidak ditemukan.');
        }

        $verification = EmailVerificationToken::where('user_id', $userId)
            ->where('token', $request->input('otp_code'))
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        $user = $verification->user;
        $user->email_verified_at = now();
        $user->save();

        $verification->delete();

        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect($this->redirectByRole($user))->with('success', 'Verifikasi email berhasil. Anda sudah masuk ke dalam sistem.');
    }

    /**
     * Menampilkan form verifikasi OTP.
     */
    public function showOtpVerificationForm()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi OTP tidak ditemukan.');
        }
        return view('auth.otp_verification');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ===========================
    // Metode Helper Privat
    // ===========================

    /**
     * Mengarahkan user berdasarkan role.
     * SUDAH DIPERBAIKI: Menggunakan metode hasRole() dari Spatie.
     */
    private function redirectByRole(User $user)
    {
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('kajur')) {
            return route('kajur.dashboard');
        }
        if ($user->hasRole('kaprodi')) {
            return route('kaprodi.dashboard');
        }
        if ($user->hasRole('dosen')) {
            return route('dosen.dashboard');
        }
        // Fallback untuk mahasiswa atau jika role lain belum terdefinisi
        return route('dashboard.mahasiswa');
    }

    /**
     * Mengirim email verifikasi berisi kode OTP.
     */
    private function sendEmailVerification(User $user)
    {
        $otpCode = random_int(100000, 999999);

        EmailVerificationToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $otpCode, 'created_at' => now()]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $otpCode));
    }
}
