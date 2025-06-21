@extends('layouts.template.homepage')

@section('title', 'Verifikasi Kode OTP')

@section('content')
    <div class="main-panel min-vh-100 d-flex align-items-center"
        style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); padding-top: 7rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4 transform-hover">
                        <div class="card-header bg-transparent border-0 text-center py-4">
                            <h4 class="mb-0 fw-bold" style="color: #0A2472;">Verifikasi Kode OTP</h4>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger bg-danger-subtle border-0 rounded-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('auth.otp.verify') }}">
                                @csrf
                                <div class="mb-3 floating-label">
                                    <label for="otp" class="form-label text-muted">Kode OTP</label>
                                    <input id="otp" type="text" name="otp" maxlength="6" required autofocus
                                        class="form-control rounded-3 input-hover border-0 bg-light"
                                        placeholder="Masukkan kode OTP" />
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-3 py-2 btn-hover gradient-button">
                                        Verifikasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .transform-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .transform-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .floating-label input::placeholder {
            color: #adb5bd;
            transition: opacity 0.3s ease;
        }

        .floating-label input:focus::placeholder {
            opacity: 0;
        }

        .input-hover {
            transition: all 0.3s ease;
        }

        .input-hover:focus {
            transform: scale(1.02);
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {  
            transform: scale(1.05);
        }

        .gradient-button {
            background: linear-gradient(45deg, #0c41de, #0c41de,#a039fb, #a039fb, #0c41de, #0c41de);
            background-size: 200% 200%;
            animation: gradient 4s ease infinite;
            border: none;
        }

        .link-hover {
            position: relative;
            transition: all 0.3s ease;
        }

        .link-hover:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--bs-primary);
            transition: width 0.3s ease;
        }

        .link-hover:hover:after {
            width: 100%;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>

    {{-- Toggle Password Script --}}
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
