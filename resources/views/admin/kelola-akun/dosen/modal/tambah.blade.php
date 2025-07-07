<!-- Modal Tambah Akun Dosen -->
<div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="tambahAkunModalLabel">Tambah Akun Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Menampilkan error umum jika ada --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('akun-dosen.store') }}" method="POST" id="formTambahAkun">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" placeholder="Masukkan nama dosen" value="{{ old('nama') }}" required>
                        <label for="nama">Nama</label>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" placeholder="Masukkan email dosen" value="{{ old('email') }}" required>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('nidn') is-invalid @enderror" id="nidn"
                            name="nidn" placeholder="Masukkan NIDN" value="{{ old('nidn') }}" required>
                        <label for="nidn">NIDN</label>
                        @error('nidn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ✅ PERBAIKAN: Dropdown Role sekarang mengirim 'role_name' --}}
                    <div class="form-floating mb-3">
                        <select class="form-select @error('role_name') is-invalid @enderror" id="role_name"
                            name="role_name">
                            <option value="" selected>Default (Hanya Dosen)</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ old('role_name') == $role->name ? 'selected' : '' }}>
                                    {{-- Mengubah nama role menjadi lebih mudah dibaca, contoh: 'kaprodi-d3' -> 'Kaprodi D3' --}}
                                    {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        <label for="role_name">Jabatan Tambahan (Opsional)</label>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input password --}}
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukkan password" required
                            autocomplete="new-password">
                        <label for="password">Password</label>
                        <button type="button"
                            class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                            id="togglePassword" tabindex="-1" style="z-index: 10;">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input konfirmasi password --}}
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" placeholder="Konfirmasi password" required
                            autocomplete="new-password">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <button type="button"
                            class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                            id="togglePasswordConfirmation" tabindex="-1" style="z-index: 10;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Tambah Akun Dosen</button>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menampilkan/menyembunyikan password
            function togglePasswordVisibility(toggleBtnId, passwordInputId) {
                const toggleButton = document.getElementById(toggleBtnId);
                const passwordInput = document.getElementById(passwordInputId);
                if (toggleButton && passwordInput) {
                    toggleButton.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                        passwordInput.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }
            }

            togglePasswordVisibility('togglePassword', 'password');
            togglePasswordVisibility('togglePasswordConfirmation', 'password_confirmation');
        });
    </script>
@endpush
