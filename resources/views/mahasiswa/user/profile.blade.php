@extends('layouts.template.mahasiswa')

@section('title', 'Profil Mahasiswa')

@section('content')
    <div class="container mt-4">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-user-circle me-2 text-primary"></i>Profil Mahasiswa</h3>
            </div>

            <!-- Avatar -->
            <div class="text-center position-relative mb-4" style="width: 140px; margin: 0 auto;">
                <div style="position: relative; display: inline-block;">
                    <img id="avatarPreview"
                        src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://placehold.co/400?text=Profile' }}"
                        class="rounded-circle border border-3 border-primary shadow-sm" alt="Foto Mahasiswa"
                        style="width: 140px; height: 140px; object-fit: cover;">

                    <label for="avatarInput"
                        class="position-absolute bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center shadow"
                        style="width: 34px; height: 34px; bottom: 2px; right: 2px; cursor: pointer;">
                        <i class="fas fa-pencil-alt small"></i>
                    </label>
                </div>

                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*"
                    onchange="previewAvatar(event)">
            </div>

            <!-- Data User -->
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-user me-1 text-primary"></i>Nama:
                </label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-envelope me-1 text-primary"></i>Email:
                </label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Mahasiswa -->
            <div class="row mb-3">
                <label for="nim" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-id-badge me-1 text-primary"></i>NIM:
                </label>
                <div class="col-sm-10">
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                        class="form-control @error('nim') is-invalid @enderror">
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="angkatan" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-1 text-primary"></i>Angkatan:
                </label>
                <div class="col-sm-10">
                    <input type="number" id="angkatan" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                        class="form-control @error('angkatan') is-invalid @enderror">
                    @error('angkatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <label for="prodi" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-graduation-cap me-1 text-primary"></i>Program Studi:
                </label>
                <div class="col-sm-10">
                    <select id="prodi" name="prodi" class="form-select @error('prodi') is-invalid @enderror">
                        <option value="D3"
                            {{ strtoupper(old('prodi', $mahasiswa->prodi)) === 'D3' ? 'selected' : '' }}>
                            D3 Bahasa Inggris
                        </option>
                        <option value="D4"
                            {{ strtoupper(old('prodi', $mahasiswa->prodi)) === 'D4' ? 'selected' : '' }}>
                            D4 Bahasa Inggris
                        </option>
                    </select>
                    @error('prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <label for="kelas" class="col-sm-2 col-form-label fw-semibold">
                    <i class="fas fa-chalkboard-teacher me-1 text-primary"></i>Kelas:
                </label>
                <div class="col-sm-10">
                    <select id="kelas" name="kelas" class="form-select @error('kelas') is-invalid @enderror">
                        <!-- Diisi via JavaScript -->
                    </select>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Perbarui Profil
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewAvatar(event) {
            const input = event.target;
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatarPreview').src = reader.result;
            };
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }

        const kelasOptions = {
            D3: ['a', 'b', 'c'],
            D4: ['a', 'b']
        };

        function updateKelasDropdown() {
            const prodi = document.getElementById('prodi').value.toUpperCase();
            const kelasSelect = document.getElementById('kelas');
            const selectedKelas = "{{ strtolower(old('kelas', $mahasiswa->kelas)) }}";

            kelasSelect.innerHTML = '';

            if (kelasOptions[prodi]) {
                kelasOptions[prodi].forEach(kls => {
                    const option = document.createElement('option');
                    option.value = kls;
                    option.textContent = 'Kelas ' + kls.toUpperCase();
                    if (kls === selectedKelas) {
                        option.selected = true;
                    }
                    kelasSelect.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('prodi').addEventListener('change', updateKelasDropdown);
            updateKelasDropdown();
        });

        @if (session('success'))
            swal({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-primary"
                    }
                }
            });
        @endif
    </script>
@endpush
