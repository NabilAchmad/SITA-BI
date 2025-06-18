@extends('layouts.template.homepage')
@section('title', 'Login')
@section('content')
    <div class="main-panel mt-5">
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeIn">
                        <div class="card-header bg-gradient text-white text-center rounded-top-4" style="background: linear-gradient(45deg, #1a237e, #0d47a1);">
                            <h3 class="mb-0 fw-bold">Welcome Back!</h3>
                        </div>
                        <div class="card-body p-4">
                            @if (session('success'))
                                <div class="alert alert-success animate__animated animate__fadeInDown" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger animate__animated animate__shakeX" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('info'))
                                <div class="alert alert-info animate__animated animate__fadeInDown" role="alert">
                                    {{ session('info') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger animate__animated animate__shakeX">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>- {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                            autofocus class="form-control" placeholder="Enter your email" />
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                        <input id="password" type="password" name="password" required
                                            class="form-control" placeholder="Enter your password" />
                                        <button class="btn btn-light border" type="button"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold btn-gradient">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-white border-0 rounded-bottom-4 py-4">
                            <p class="mb-0">
                                New to our platform? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Create an account</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-gradient {
            background: linear-gradient(45deg, #001f3f, #003264, #094eb5, #094eb5, #003264, #001f3f);
            background-size: 400% 400%;
            animation: gradientShift 4s ease infinite;
            transition: all 0.3s ease;
            border: none;
            color: white;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        @keyframes gradientShift {
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

    {{-- Add animate.css CDN if not already included --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

@endsection