<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail; // Asumsi Anda punya Mailable ini
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
    // Metode Login, Logout, dan Helper (Tidak Diubah)
    // ===========================================

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
     * Langkah 1: Menangani data registrasi, menyimpan ke session, dan mengirim OTP.
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
     * Langkah 2: Menampilkan form untuk memasukkan OTP.
     */
    public function showOtpVerificationForm()
    {
        // Pastikan pengguna datang dari halaman registrasi, bukan akses langsung
        if (!session()->has('registration_data')) {
            return redirect()->route('auth.register')->with('error', 'Sesi registrasi tidak ditemukan. Silakan mulai dari awal.');
        }
        return view('auth.otp_verification'); // Pastikan nama view-nya benar
    }

    /**
     * Langkah 3: Memverifikasi OTP dan membuat user jika valid.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => ['required', 'numeric', 'digits:6']]);

        // Ambil data dari session
        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('auth.register')->with('error', 'Sesi Anda telah berakhir. Silakan registrasi ulang.');
        }

        $email = $registrationData['email'];
        $otpCode = $request->input('otp_code');

        // Cari token berdasarkan email dan kode OTP yang dimasukkan
        $verificationToken = EmailVerificationToken::where('email', $email)
            ->where('token', $otpCode)
            ->first();

        // Cek jika token tidak ada atau sudah kedaluwarsa (misal: lebih dari 10 menit)
        if (!$verificationToken || Carbon::parse($verificationToken->created_at)->addMinutes(10)->isPast()) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Jika OTP valid, simpan data ke database
        try {
            $user = DB::transaction(function () use ($registrationData) {
                $user = User::create([
                    'name'              => $registrationData['name'],
                    'email'             => $registrationData['email'],
                    'password'          => Hash::make($registrationData['password']),
                    'email_verified_at' => now(), // Langsung set terverifikasi
                ]);

                $user->assignRole('mahasiswa');

                Mahasiswa::create([
                    'user_id'  => $user->id,
                    'nim'      => $registrationData['nim'],
                    'prodi'    => $registrationData['prodi'],
                    'kelas'    => $registrationData['kelas'],
                    'angkatan' => '20' . substr($registrationData['nim'], 0, 2), // Logika angkatan yang lebih aman
                ]);

                return $user;
            });

            // Hapus token setelah berhasil digunakan
            $verificationToken->delete();

            // Hapus data dari session
            session()->forget(['registration_data']);

            // Login-kan user secara otomatis
            Auth::login($user);

            return redirect($this->redirectByRole($user))->with('success', 'Verifikasi berhasil! Selamat datang.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error jika perlu: Log::error($e->getMessage());
            return redirect()->route('auth.register')->with('error', 'Registrasi gagal karena terjadi kesalahan server. Silakan coba lagi.');
        }
    }

    /**
     * Menangani permintaan kirim ulang OTP.
     */
    public function resendOtp(Request $request)
    {
        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('auth.register')->with('error', 'Sesi Anda telah berakhir. Silakan registrasi ulang.');
        }

        $email = $registrationData['email'];
        $name = $registrationData['name'];
        $token = EmailVerificationToken::where('email', $email)->first();

        // Tambahkan cooldown 60 detik untuk mencegah spam
        if ($token && Carbon::parse($token->created_at)->addSeconds(60)->isFuture()) {
            $secondsLeft = Carbon::parse($token->created_at)->addSeconds(60)->diffInSeconds(now());
            return back()->with('error', "Harap tunggu {$secondsLeft} detik lagi sebelum meminta kode baru.");
        }

        // Kirim email verifikasi baru
        $this->sendEmailVerification($email, $name);

        return back()->with('success', 'Kode OTP baru telah berhasil dikirim ke email Anda.');
    }

    /**
     * Metode Helper: Membuat dan mengirim token verifikasi email.
     * Diperbaiki untuk menggunakan email, bukan User object.
     */
    private function sendEmailVerification(User $user): void
    {
        // Generate 6 digit OTP
        $otpCode = random_int(100000, 999999);

        // Simpan/update token dengan masa berlaku 30 menit
        EmailVerificationToken::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => $otpCode,
                'created_at' => now()
            ]
        );

        try {
            // Kirim email secara synchronous
            Mail::to($user->email)->send(new VerifyEmail($user, $otpCode));
        } catch (\Exception $e) {
            // Hapus token yang sudah disimpan jika email gagal dikirim
            EmailVerificationToken::where('email', $user->email)->delete();

            throw new \RuntimeException('Gagal mengirim email verifikasi: ' . $e->getMessage());
        }
    }
}
