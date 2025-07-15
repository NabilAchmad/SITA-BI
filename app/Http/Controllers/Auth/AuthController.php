<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\EmailVerificationToken;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // ===========================================
    // Metode Login, Logout, dan Helper
    // ===========================================

    /**
     * Menampilkan form login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login dengan bypass verifikasi untuk admin/dosen.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // 🛑 Pengecekan Bypass Verifikasi
        if ($user && is_null($user->email_verified_at)) {
            // Cek apakah user BUKAN admin atau dosen.
            // Hanya peran selain ini (seperti mahasiswa) yang WAJIB verifikasi.
            if (!$user->hasRole('admin') && !$user->hasAnyRole(['kajur', 'kaprodi-d3', 'kaprodi-d4', 'dosen'])) {

                // Arahkan ke halaman verifikasi OTP karena ini bukan admin/dosen
                session(['otp_user_id' => $user->id]);
                return redirect()->route('auth.otp.verify.form')
                    ->with('error', 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk kode OTP.');
            }
            // Jika user adalah admin atau dosen, maka kode ini akan dilewati
            // dan proses login dilanjutkan di bawah.
        }

        // Proses otentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $user->load('roles');

            return redirect()->intended($this->redirectByRole($user));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
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

    /**
     * Mengarahkan user berdasarkan peran.
     */
    private function redirectByRole(User $user)
    {
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }
        if ($user->hasAnyRole(['kajur', 'kaprodi-d3', 'kaprodi-d4', 'dosen'])) {
            return route('dosen.dashboard');
        }
        return route('mahasiswa.dashboard');
    }


    // ===========================================
    // Alur Registrasi dan Verifikasi OTP (Diperbaiki)
    // ===========================================

    /**
     * Menampilkan form registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Langkah 1: Menangani registrasi, membuat user (belum terverifikasi), dan mengirim OTP.
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
            $user = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    // email_verified_at tetap NULL secara default
                ]);

                // Memberikan role 'mahasiswa'
                $user->assignRole('mahasiswa');

                Mahasiswa::create([
                    'user_id'  => $user->id,
                    'nim'      => $validated['nim'],
                    'prodi'    => $validated['prodi'],
                    'kelas'    => $validated['kelas'],
                    'angkatan' => '20' . substr($validated['nim'], 0, 2),
                ]);

                return $user;
            });

            // Mengirim OTP verifikasi
            $this->sendEmailVerification($user);

            // Menyimpan ID user yang perlu diverifikasi ke dalam sesi
            session(['otp_user_id' => $user->id]);

            return redirect()->route('auth.otp.verify.form')->with('success', 'Registrasi awal berhasil. Silakan masukkan kode OTP yang telah dikirim ke email Anda.');
        } catch (\RuntimeException $e) {
            DB::rollBack();
            Log::error('Registrasi Gagal - Pengiriman Email: ' . $e->getMessage());
            return back()->with('error', 'Registrasi gagal karena email tidak dapat dikirim. Silakan coba lagi.')->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registrasi Gagal - DB Error: ' . $e->getMessage());
            return back()->with('error', 'Registrasi gagal, terjadi kesalahan pada server. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Langkah 2: Menampilkan form untuk memasukkan OTP.
     */
    public function showOtpVerificationForm()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('auth.register')->with('error', 'Sesi verifikasi tidak ditemukan. Silakan registrasi atau login.');
        }

        $user = User::find(session('otp_user_id'));
        if (!$user) {
            session()->forget('otp_user_id');
            return redirect()->route('auth.register')->with('error', 'Data pengguna tidak ditemukan. Silakan registrasi ulang.');
        }

        return view('auth.otp_verification', ['email' => $user->email]);
    }

    /**
     * Langkah 3: Memverifikasi OTP dan mengaktifkan user jika valid.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => ['required', 'numeric', 'digits:6']]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('auth.register')->with('error', 'Sesi Anda telah berakhir. Silakan registrasi ulang.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('auth.register')->with('error', 'User tidak ditemukan. Silakan registrasi ulang.');
        }

        $otpCode = $request->input('otp_code');

        $verificationToken = EmailVerificationToken::where('email', $user->email)
            ->where('token', $otpCode)
            ->first();

        if (!$verificationToken || Carbon::parse($verificationToken->created_at)->addMinutes(10)->isPast()) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        try {
            DB::transaction(function () use ($user, $verificationToken) {
                $user->email_verified_at = now();
                $user->save();
                $verificationToken->delete();
            });

            session()->forget('otp_user_id');

            Auth::login($user);

            return redirect($this->redirectByRole($user))->with('success', 'Verifikasi berhasil! Selamat datang.');
        } catch (\Exception $e) {
            Log::error('Verifikasi OTP Gagal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.');
        }
    }

    /**
     * Menangani permintaan kirim ulang OTP.
     */
    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('auth.register')->with('error', 'Sesi Anda telah berakhir. Silakan registrasi ulang.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('auth.register')->with('error', 'User tidak ditemukan. Silakan registrasi ulang.');
        }

        $token = EmailVerificationToken::where('email', $user->email)->first();

        if ($token && Carbon::parse($token->created_at)->addSeconds(60)->isFuture()) {
            $secondsLeft = Carbon::parse($token->created_at)->addSeconds(60)->diffInSeconds(now());
            return back()->with('error', "Harap tunggu {$secondsLeft} detik lagi sebelum meminta kode baru.");
        }

        try {
            $this->sendEmailVerification($user);
            return back()->with('success', 'Kode OTP baru telah berhasil dikirim ke email Anda.');
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Gagal mengirim ulang OTP. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Metode Helper: Membuat dan mengirim token verifikasi email.
     */
    private function sendEmailVerification(User $user): void
    {
        $otpCode = random_int(100000, 999999);

        EmailVerificationToken::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => $otpCode,
                'created_at' => now()
            ]
        );

        try {
            Mail::to($user->email)->send(new VerifyEmail($user, $otpCode));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email verifikasi ke ' . $user->email . ': ' . $e->getMessage());
            EmailVerificationToken::where('email', $user->email)->delete();
            throw new \RuntimeException('Gagal mengirim email verifikasi: ' . $e->getMessage());
        }
    }
}
