@extends('layouts.template.homepage')
@section('title', 'Register')
@section('content')
    <div class="main-panel" style="padding-top: 100px; padding-bottom: 100px;">
        <div class="container d-flex align-items-center justify-content-center">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-primary text-white text-center rounded-top-4">
                            <h3 class="mb-0">Register</h3>
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
                            <form method="POST" action="{{ route('register.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                        autofocus class="form-control form-control-lg" />
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                        class="form-control form-control-lg" />
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input id="password" type="password" name="password" required
                                            class="form-control form-control-lg" />
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            required class="form-control form-control-lg" />
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password_confirmation', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-white border-0 rounded-bottom-4">
                            <p class="mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
