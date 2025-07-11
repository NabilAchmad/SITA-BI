@extends('layouts.template.homepage')
@section('title', 'Verifikasi OTP')

@section('content')
    <div class="container py-5" style="margin-top: 80px; margin-bottom: 60px;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow border-0 rounded-4 hover-shadow" style="transition: all 0.3s ease;">
                    <div class="card-header text-center bg-white border-0 pt-4">
                        <h4 class="fw-bold text-primary animate__animated animate__fadeIn">Verifikasi Kode OTP</h4>
                        <p class="text-muted">Masukkan kode OTP yang telah dikirim ke email Anda</p>
                    </div>
                    <div class="card-body px-4 py-3">
                        {{-- Menampilkan pesan success atau error --}}
                        @if (session('success'))
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger animate__animated animate__shakeX">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form Verifikasi --}}
                        <form method="POST" action="{{ route('auth.otp.verify.post') }}" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="mb-3 form-floating">
                                <input type="text" name="otp_code" id="otp_code" maxlength="6" required autofocus
                                    class="form-control" value="{{ old('otp_code') }}" placeholder="Masukkan kode OTP">
                                <label for="otp_code">Kode OTP</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Verifikasi
                                </button>
                            </div>
                        </form>

                        {{-- 👇 FORM UNTUK KIRIM ULANG OTP --}}
                        <div class="text-center mt-3">
                            <small>Tidak menerima kode?
                                <form action="{{ route('auth.otp.resend') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" id="resend-btn"
                                        class="btn btn-link text-decoration-none p-0 m-0 align-baseline">
                                        Kirim ulang
                                    </button>
                                </form>
                            </small>
                        </div>

                    </div>
                    <div class="card-footer text-center bg-white border-0 py-3">
                        <small>
                            Kembali ke
                            <a href="{{ route('auth.login') }}" class="text-decoration-none fw-bold">Login</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resendBtn = document.getElementById('resend-btn');
            const cooldownKey = 'otp_cooldown_timestamp';
            let countdownInterval;

            function startCooldown(seconds) {
                resendBtn.disabled = true;
                let remaining = seconds;

                countdownInterval = setInterval(() => {
                    resendBtn.textContent = `Kirim ulang dalam ${remaining} detik`;
                    remaining--;
                    if (remaining < 0) {
                        clearInterval(countdownInterval);
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Kirim ulang';
                        localStorage.removeItem(cooldownKey);
                    }
                }, 1000);
            }

            // Cek jika ada cooldown yang tersimpan di localStorage saat halaman dimuat
            const savedTimestamp = localStorage.getItem(cooldownKey);
            if (savedTimestamp) {
                const secondsPassed = Math.floor((Date.now() - savedTimestamp) / 1000);
                if (secondsPassed < 60) {
                    startCooldown(60 - secondsPassed);
                } else {
                    localStorage.removeItem(cooldownKey);
                }
            }

            // Tambahkan event listener untuk form kirim ulang
            resendBtn.closest('form').addEventListener('submit', function() {
                if (!resendBtn.disabled) {
                    localStorage.setItem(cooldownKey, Date.now());
                    startCooldown(60);
                }
            });
        });
    </script>

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .btn-link {
            font-weight: bold;
        }
    </style>
@endpush
