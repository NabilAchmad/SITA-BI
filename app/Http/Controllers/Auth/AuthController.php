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
use Carbon\Carbon; // <-- PENTING: Tambahkan use statement ini di atas

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

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Membersihkan cache peran Spatie dan memuat ulang relasi
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $user->load('roles');

            return redirect()->intended($this->redirectByRole($user));
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
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'nim'      => ['required', 'string', 'max:10', 'unique:mahasiswa'],
            'prodi'    => ['required', 'in:d3,d4'],
            'kelas'    => ['required', 'string', 'max:1'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('mahasiswa');

            Mahasiswa::create([
                'user_id'  => $user->id,
                'nim'      => $validated['nim'],
                'prodi'    => $validated['prodi'],
                'kelas'    => $validated['kelas'],
                'angkatan' => 2000 + intval(substr($validated['nim'], 0, 2)),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Registrasi gagal, terjadi kesalahan.')->withInput();
        }

        $this->sendEmailVerification($user);
        session(['otp_user_id' => $user->id]);
        return redirect()->route('auth.otp.verify.form')->with('success', 'Registrasi berhasil. Silakan periksa email Anda untuk kode OTP.');
    }

    /**
     * Menangani verifikasi OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => ['required', 'digits:6']]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('auth.login')->with('error', 'Sesi verifikasi OTP tidak ditemukan.');
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
            return redirect()->route('auth.login')->with('error', 'Sesi verifikasi OTP tidak ditemukan.');
        }
        return view('auth.otp_verification');
    }

    /**
     * Menangani permintaan kirim ulang OTP.
     */
    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('auth.login')->with('error', 'Sesi verifikasi OTP tidak ditemukan atau sudah kadaluarsa.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Pengguna tidak ditemukan.');
        }

        $token = EmailVerificationToken::where('user_id', $user->id)->first();

        // =================================================================
        // PERBAIKAN ADA DI SINI
        // Kita gunakan Carbon::parse() untuk memastikan $token->created_at
        // adalah objek Carbon sebelum memanggil addSeconds().
        // =================================================================
        if ($token && Carbon::parse($token->created_at)->addSeconds(60)->isFuture()) {
            $secondsLeft = now()->diffInSeconds(Carbon::parse($token->created_at)->addSeconds(60));
            return back()->withErrors(['otp_code' => "Harap tunggu {$secondsLeft} detik lagi sebelum meminta kode baru."]);
        }

        // Kirim email verifikasi baru
        $this->sendEmailVerification($user);

        return redirect()->route('auth.otp.verify.form')
            ->with('success', 'Kode OTP baru telah berhasil dikirim ke email Anda.');
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

    /**
     * Membuat dan mengirim token verifikasi email.
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
