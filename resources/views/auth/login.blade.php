@extends('layouts.template.homepage')
@section('title', 'Login')
@section('content')
    <div class="main-panel mt-5">
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-primary text-white text-center rounded-top-4">
                            <h3 class="mb-0">Login</h3>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
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
                                        autofocus class="form-control form-control-lg" placeholder="Enter your email" />
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input id="password" type="password" name="password" required
                                            class="form-control form-control-lg" placeholder="Enter your password" />
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-white border-0 rounded-bottom-4">
                            <p class="mb-0">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    {{-- <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const svg = btn.querySelector('svg');
            if (input.type === "password") {
                input.type = "text";
                svg.innerHTML = '<path d="M13.359 11.238a6.5 6.5 0 0 0 1.292-3.238 6.5 6.5 0 0 0-12.5 0 6.5 6.5 0 0 0 1.292 3.238l-1.415 1.415a.5.5 0 0 0 .708.708l12-12a.5.5 0 0 0-.708-.708l-1.415 1.415z"/><path d="M11.297 9.297a3 3 0 0 1-4.243-4.243"/>';
            } else {
                input.type = "password";
                svg.innerHTML = '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/><path d="M8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>';
            }
        }
    </script> --}}
</body>

</html>
=======
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
>>>>>>> admin
