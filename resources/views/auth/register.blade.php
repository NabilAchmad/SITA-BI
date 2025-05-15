<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .password-toggle-btn {
            background: transparent;
            border: none;
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body">
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
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                    class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    class="form-control" />
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" required
                                    class="form-control" />
                                <button type="button" class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" 
                                    style="border:none; background: transparent; padding: 0;" onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control" />
                                {{-- <button type="button" class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" 
                                    style="border:none; background: transparent; padding: 0;" onclick="togglePassword('password_confirmation', this)" aria-label="Toggle password visibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                                    </svg>
                                </button> --}}
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">
                            Already have an account?
                            <a href="{{ route('login') }}">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
