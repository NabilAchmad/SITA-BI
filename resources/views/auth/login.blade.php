@extends('layouts.template.homepage')
@section('title', 'Login')
@section('content')
    <div class="main-panel mt-5">
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                        <div class="card-header text-center position-relative overflow-hidden" style="background: linear-gradient(-45deg, #1a237e, #0277bd); height: 80px;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-size: 200% 200%; animation: gradient 5s ease infinite;"></div>
                            <h3 class="mb-0 text-white position-relative pt-3" style="font-family: 'Poppins', sans-serif; font-weight: 600; letter-spacing: 2px;">SITA-BI</h3>
                        </div>
                        <div class="card-body p-4">
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
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                        autofocus class="form-control rounded-pill border-0 shadow-sm" placeholder="Enter your email" />
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input id="password" type="password" name="password" required
                                            class="form-control rounded-pill border-0 shadow-sm" placeholder="Enter your password" />
                                        <button class="btn btn-light rounded-pill ms-2 shadow-sm" type="button"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill shadow hover-lift" style="background: linear-gradient(to right, #1a237e, #0277bd); border: none;">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-transparent border-0 py-3">
                            <p class="mb-0 text-muted">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none" style="color: #0277bd; font-weight: 500;">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .hover-lift {
        transition: transform 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
    }
    @keyframes gradient {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
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
