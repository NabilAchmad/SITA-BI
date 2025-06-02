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
                <form action="{{ route('akun-dosen.store') }}" method="POST" id="formTambahAkun">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama"
                            placeholder="Masukkan nama dosen" required>
                        <label for="nama">Nama</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Masukkan email dosen" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nidn" name="nidn"
                            placeholder="Masukkan NIDN" required>
                        <label for="nidn">NIDN</label>
                    </div>

                    {{-- Dropdown Role --}}
                    <div class="form-floating mb-3">
                        <select class="form-select" id="role_id" name="role_id">
                            <option value="" selected>Default Dosen</option>
                            @foreach ($roles as $role)
                                @if (in_array($role->nama_role, ['kaprodi', 'kajur']))
                                    <option value="{{ $role->id }}">{{ ucfirst($role->nama_role) }} -
                                        {{ $role->deskripsi }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="role_id">Jabatan (Opsional)</label>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Masukkan password" required>
                        <label for="password">Password</label>
                        <button type="button"
                            class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                            id="togglePassword" tabindex="-1" style="z-index: 10;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn w-100">Tambah Akun Dosen</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                // Toggle tipe input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle icon mata dan mata tertutup
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });

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
