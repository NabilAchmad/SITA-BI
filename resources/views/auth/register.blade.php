@extends('layouts.template.homepage')
@section('title', 'Register')

@section('content')
    <div class="container py-5" style="margin-top: 80px; margin-bottom: 60px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow border-0 rounded-4 hover-shadow" style="transition: all 0.3s ease;">
                    <div class="card-header text-center bg-white border-0 pt-4">
                        <h4 class="fw-bold text-primary animate__animated animate__fadeIn">Register Account</h4>
                        <p class="text-muted">Create your account to get started</p>
                    </div>
                    <div class="card-body px-4 py-3">
                        @if ($errors->any())
                            <div class="alert alert-danger animate__animated animate__shakeX">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}" class="needs-validation">
                            @csrf

                            <div class="mb-3 form-floating">
                                <input id="name" type="text" name="name" class="form-control"
                                    value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">
                                <label for="name">Nama Lengkap</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <input id="email" type="email" name="email" class="form-control"
                                    value="{{ old('email') }}" required placeholder="Masukkan email">
                                <label for="email">Email</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <input id="nim" type="text" name="nim" class="form-control"
                                    value="{{ old('nim') }}" required placeholder="Masukkan NIM">
                                <label for="nim">NIM</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <select id="prodi" name="prodi" class="form-select" required>
                                    <option value="" hidden>Pilih Program Studi</option>
                                    <option value="d3" {{ old('prodi') == 'd3' ? 'selected' : '' }}>D3 Bahasa Inggris</option>
                                    <option value="d4" {{ old('prodi') == 'd4' ? 'selected' : '' }}>D4 Bahasa Inggris</option>
                                </select>
                                <label for="prodi">Program Studi</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <select id="kelas" name="kelas" class="form-select" required>
                                    <option value="" hidden>Pilih kelas</option>
                                </select>
                                <label for="kelas">Kelas</label>
                            </div>

                            <div class="mb-3">
                                <div class="form-floating">
                                    <input id="password" type="password" name="password" class="form-control" required
                                        placeholder="Buat password">
                                    <label for="password">Password</label>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                                        onclick="togglePassword('password', this)" style="z-index: 5;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-floating">
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        class="form-control" required placeholder="Ulangi password">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                                        onclick="togglePassword('password_confirmation', this)" style="z-index: 5;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg animate__animated animate__pulse">
                                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 py-3">
                        <small>
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login di sini</a>
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

        document.addEventListener("DOMContentLoaded", function() {
            const kelasSelect = document.getElementById("kelas");
            const prodiSelect = document.getElementById("prodi");

            const kelasMap = {
                d3: ["A", "B", "C"],
                d4: ["A", "B"]
            };

            function updateKelasOptions(prodi) {
                kelasSelect.innerHTML = '<option value="" hidden>Pilih kelas</option>';
                if (kelasMap[prodi]) {
                    kelasMap[prodi].forEach(function(kelas) {
                        const option = document.createElement("option");
                        option.value = kelas.toLowerCase();
                        option.text = "Kelas " + kelas;
                        kelasSelect.appendChild(option);
                    });
                }
            }

            prodiSelect.addEventListener("change", function() {
                updateKelasOptions(this.value);
            });

            // Pre-select if old value exists (server validation fallback)
            const selectedProdi = "{{ old('prodi') }}";
            const selectedKelas = "{{ old('kelas') }}";
            if (selectedProdi) {
                updateKelasOptions(selectedProdi);
                if (selectedKelas) {
                    kelasSelect.value = selectedKelas.toLowerCase();
                }
            }

            // Add floating label animation
            document.querySelectorAll('.form-control, .form-select').forEach(input => {
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