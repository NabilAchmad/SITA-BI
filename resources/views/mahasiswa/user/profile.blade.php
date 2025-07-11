@extends('layouts.template.main')

@section('title', 'Pengaturan Profil')

@push('styles')
    <style>
        .profile-container {
            display: flex;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, .07);
            overflow: hidden;
            min-height: 80vh;
        }

        .profile-sidebar {
            width: 350px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin-bottom: 1rem;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-edit-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(13, 110, 253, 0.6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-avatar-wrapper:hover .profile-avatar-edit-overlay {
            opacity: 1;
        }

        .info-list {
            list-style: none;
            padding: 0;
            width: 100%;
            margin-top: 2rem;
        }

        .info-list li {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list .icon {
            color: #0d6efd;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }

        .info-list .label {
            font-weight: 600;
            color: #212529;
        }

        .info-list .value {
            margin-left: auto;
            color: #6c757d;
            text-align: right;
        }

        .profile-content {
            flex: 1;
            padding: 2.5rem 3rem;
            overflow-y: auto;
        }

        .profile-content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .form-section {
            margin-bottom: 3rem;
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #343a40;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        @media (max-width: 991.98px) {
            .profile-container {
                flex-direction: column;
            }

            .profile-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }

            .profile-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <form id="profile-form" action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-container">
                <aside class="profile-sidebar">
                    <div class="profile-avatar-wrapper">
                        <img id="avatarPreview"
                            src="{{ $mahasiswa->user?->photo ? asset('storage/' . $mahasiswa->user->photo) : 'https://placehold.co/150x150/EBF4FF/76839A?text=Profile' }}"
                            alt="Foto Profil" class="profile-avatar">
                        <label for="photoInput" class="profile-avatar-edit-overlay" title="Ubah foto profil">
                            <i class="fas fa-camera fa-2x"></i>
                        </label>
                        <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                    </div>

                    <div class="text-center mt-2">
                        <h4 class="mb-1">{{ $mahasiswa->user?->name ?? 'Nama Pengguna' }}</h4>
                        <p class="text-muted mb-0">{{ $mahasiswa->user?->email ?? 'email@contoh.com' }}</p>
                    </div>

                    <ul class="info-list">
                        <li>
                            <i class="fas fa-id-card icon"></i>
                            <span class="label">NIM</span>
                            <span class="value">{{ $mahasiswa->nim }}</span>
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt icon"></i>
                            <span class="label">Angkatan</span>
                            <span class="value">{{ $mahasiswa->angkatan }}</span>
                        </li>
                        <li>
                            <i class="fas fa-graduation-cap icon"></i>
                            <span class="label">Prodi</span>
                            <span class="value">{{ strtoupper($mahasiswa->prodi) }}</span>
                        </li>
                        <li>
                            <i class="fas fa-chalkboard-teacher icon"></i>
                            <span class="label">Kelas</span>
                            <span class="value">Kelas {{ strtoupper($mahasiswa->kelas) }}</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle icon"></i>
                            <span class="label">Status</span>
                            <span class="value text-capitalize text-success fw-bold">{{ $mahasiswa->status }}</span>
                        </li>
                    </ul>
                </aside>

                <main class="profile-content">
                    <div class="profile-content-header">
                        <h2 class="h3 fw-bold mb-0">Pengaturan Akun</h2>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                    <section class="form-section">
                        <h4 class="form-section-title">Informasi Akun</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $mahasiswa->user?->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap">
                                    <label for="name">Nama Lengkap</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $mahasiswa->user?->email) }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Alamat Email">
                                    <label for="email">Alamat Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h4 class="form-section-title">Data Akademik</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="nim_display" value="{{ $mahasiswa->nim }}"
                                        class="form-control" placeholder="NIM" readonly>
                                    <label for="nim_display">NIM (Tidak dapat diubah)</label>
                                    <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="angkatan_display" value="{{ $mahasiswa->angkatan }}"
                                        class="form-control" placeholder="Angkatan" readonly>
                                    <label for="angkatan_display">Angkatan (Tidak dapat diubah)</label>
                                    <input type="hidden" name="angkatan" value="{{ $mahasiswa->angkatan }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="prodi" name="prodi"
                                        class="form-select @error('prodi') is-invalid @enderror" aria-label="Program Studi">
                                        <option value="d3" @selected(strtolower(old('prodi', $mahasiswa->prodi)) === 'd3')>D3 Bahasa Inggris</option>
                                        <option value="d4" @selected(strtolower(old('prodi', $mahasiswa->prodi)) === 'd4')>D4 Bahasa Inggris</option>
                                    </select>
                                    <label for="prodi">Program Studi</label>
                                    @error('prodi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="kelas" name="kelas"
                                        class="form-select @error('kelas') is-invalid @enderror"
                                        aria-label="Kelas"></select>
                                    <label for="kelas">Kelas</label>
                                    @error('kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h4 class="form-section-title">Ubah Password</h4>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Password Saat Ini">
                                    <label for="current_password">Password Saat Ini</label>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Password Baru">
                                    <label for="password">Password Baru</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Konfirmasi Password Baru">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">Kosongkan bagian password jika Anda tidak ingin
                            mengubahnya.</small>
                    </section>

                </main>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Script untuk preview foto ---
            const photoInput = document.getElementById('photoInput');
            if (photoInput) {
                photoInput.addEventListener('change', function(event) {
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
                        reader.readAsDataURL(event.target.files[0]);
                    }
                });
            }

            // --- Script untuk dropdown kelas dinamis ---
            const prodiSelect = document.getElementById('prodi');
            const kelasSelect = document.getElementById('kelas');
            const kelasOptions = {
                d3: ['a','b','c'], // Sesuai enum('a','b','c') di DB
                d4: ['a','b'] // Sesuai enum('a','b','c') di DB
            };

            function updateKelasDropdown() {
                const selectedProdi = prodiSelect.value;
                // Ambil nilai 'old' atau nilai dari database, pastikan lowercase.
                const currentKelas = "{{ strtolower(old('kelas', $mahasiswa->kelas)) }}";
                kelasSelect.innerHTML = '';

                if (kelasOptions[selectedProdi]) {
                    kelasOptions[selectedProdi].forEach(kls => {
                        const option = document.createElement('option');
                        option.value = kls.toLowerCase();
                        option.textContent = 'Kelas ' + kls.toUpperCase();
                        // Bandingkan dengan lowercase untuk konsistensi
                        if (kls.toLowerCase() === currentKelas) {
                            option.selected = true;
                        }
                        kelasSelect.appendChild(option);
                    });
                }
            }
            updateKelasDropdown();
            prodiSelect.addEventListener('change', updateKelasDropdown);

            // --- Script untuk notifikasi SweetAlert2 ---
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if ($errors->any())
                let errorList = `<ul class="list-unstyled text-start ps-3" style="margin-bottom: 0;">`;
                @foreach ($errors->all() as $error)
                    errorList +=
                        `<li class="py-1"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>{{ $error }}</li>`;
                @endforeach
                errorList += `</ul>`;

                Swal.fire({
                    title: 'Gagal Validasi!',
                    html: errorList,
                    icon: 'error',
                    confirmButtonText: 'Mengerti',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            @endif
        });
    </script>
@endpush
