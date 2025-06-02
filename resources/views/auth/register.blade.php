@extends('layouts.template.homepage')
@section('title', 'Register')
@section('content')
    <div class="main-panel min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding-top: 7rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4 transform-hover" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="card-header bg-transparent border-0 text-center py-4">
                            <h4 class="mb-0 fw-bold text-primary">Register Account</h4>
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
                            <form method="POST" action="{{ route('register.post') }}">
                                @csrf
                                <div class="mb-3 floating-label">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                        autofocus class="form-control form-control-lg rounded-3 border-0 shadow-sm" placeholder="Enter your name" />
                                </div>
                                <div class="mb-3 floating-label">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                        class="form-control form-control-lg rounded-3 border-0 shadow-sm" placeholder="Enter your email" />
                                </div>
                                <div class="mb-3 floating-label">
                                    <label for="nim" class="form-label">NIM</label>
                                    <input id="nim" type="number" name="nim" value="{{ old('nim') }}" required
                                        class="form-control form-control-lg rounded-3 border-0 shadow-sm" placeholder="Enter your NIM" />
                                </div>
                                <div class="mb-3 floating-label">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group shadow-sm">
                                        <input id="password" type="password" name="password" required
                                            class="form-control form-control-lg rounded-start-3 border-0" placeholder="Create password" />
                                        <button type="button" class="btn btn-light rounded-end-3 border-0"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4 floating-label">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group shadow-sm">
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            required class="form-control form-control-lg rounded-start-3 border-0" placeholder="Confirm password" />
                                        <button type="button" class="btn btn-light rounded-end-3 border-0"
                                            onclick="togglePassword('password_confirmation', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-transparent border-0 py-3">
                            <p class="mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .transform-hover {
        transition: transform 0.3s ease;
    }
    .transform-hover:hover {
        transform: translateY(-5px);
    }
    .floating-label input::placeholder {
        color: #adb5bd;
    }
    .floating-label input:focus::placeholder {
        opacity: 0;
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
