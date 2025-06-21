@extends('layouts.template.homepage')
@section('title', 'Register')

@section('content')
    <div class="container py-5" style="margin-top: 80px; margin-bottom: 60px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header text-center bg-white border-0 pt-4">
                        <h4 class="fw-bold text-primary">Register Account</h4>
                    </div>
                    <div class="card-body px-4 py-3">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" type="text" name="name" class="form-control"
                                    value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" class="form-control"
                                    value="{{ old('email') }}" required placeholder="Masukkan email">
                            </div>

                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input id="nim" type="text" name="nim" class="form-control"
                                    value="{{ old('nim') }}" required placeholder="Masukkan NIM">
                            </div>

                            <div class="mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <select id="prodi" name="prodi" class="form-select" required>
                                    <option value="" hidden>Pilih Program Studi</option>
                                    <option value="d3" {{ old('prodi') == 'd3' ? 'selected' : '' }}>D3 Bahasa Inggris
                                    </option>
                                    <option value="d4" {{ old('prodi') == 'd4' ? 'selected' : '' }}>D4 Bahasa Inggris
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select id="kelas" name="kelas" class="form-select" required>
                                    <option value="" hidden>Pilih kelas</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input id="password" type="password" name="password" class="form-control" required
                                        placeholder="Buat password">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        class="form-control" required placeholder="Ulangi password">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 py-3">
                        <small>
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        });
    </script>
@endpush
