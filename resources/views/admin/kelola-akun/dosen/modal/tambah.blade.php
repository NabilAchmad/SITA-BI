<!-- Modal Tambah Akun Dosen -->
<!-- Modal -->
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
                @if (
                    $errors->any() &&
                        !$errors->has('nama') &&
                        !$errors->has('email') &&
                        !$errors->has('nidn') &&
                        !$errors->has('password'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('akun-dosen.store') }}" method="POST" id="formTambahAkun">
                    @csrf
                    <div class="form-floating mb-3">
                        {{-- Menambahkan old('nama') untuk menjaga input --}}
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" placeholder="Masukkan nama dosen" value="{{ old('nama') }}" required>
                        <label for="nama">Nama</label>
                        {{-- Menampilkan error spesifik untuk 'nama' --}}
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

                    {{-- Dropdown Role --}}
                    <div class="form-floating mb-3">
                        <select class="form-select @error('role_id') is-invalid @enderror" id="role_id"
                            name="role_id">
                            <option value="" selected>Default (Hanya Dosen)</option>
                            @foreach ($roles as $role)
                                {{-- Kondisi disesuaikan dengan nama role baru --}}
                                @if (in_array($role->name, ['kaprodi-d3', 'kaprodi-d4', 'kajur']))
                                    {{-- Menambahkan old() untuk select --}}
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->deskripsi }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <label for="role_id">Jabatan Tambahan (Opsional)</label>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input password --}}
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukkan password" required>
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
                            name="password_confirmation" placeholder="Konfirmasi password" required>
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
            // Toggle untuk password
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });

            // Toggle untuk konfirmasi password
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const passwordConfirmation = document.getElementById('password_confirmation');

            togglePasswordConfirmation.addEventListener('click', function() {
                const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmation.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        });
    </script>
@endpush
