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
                        @if(session('success'))
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger animate__animated animate__shakeX">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('auth.otp.verify.post') }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3 form-floating">
                                <input type="text" name="otp_code" id="otp_code" maxlength="6" required autofocus
                                    class="form-control"
                                    value="{{ old('otp_code') }}"
                                    placeholder="Masukkan kode OTP">
                                <label for="otp_code">Kode OTP</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg animate__animated animate__pulse">
                                    Verifikasi
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 py-3">
                        <small>
                            Kembali ke
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login</a>
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
        // Add floating label animation
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        }
        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: .625rem;
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            opacity: .65;
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
        }
    </style>
@endpush
