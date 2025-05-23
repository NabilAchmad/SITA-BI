@extends('layouts.template.main')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            {{-- Breadcrumb --}}
            @include('admin.kelola-akun.breadcrumbs.navlink')

            {{-- Judul halaman --}}
            <div class="text-center">
                <h4 class="card-title text-primary mb-0">Kelola Akun Mahasiswa</h4>
            </div>
        </div>

        <div class="card-body">
            <!-- Tabs prodi -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola', ['prodi' => 'D4']) }}">D4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola', ['prodi' => 'D3']) }}">D3</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form method="GET" action="{{ route('akun-mahasiswa.kelola') }}" id="searchForm"
                class="row g-2 mb-3 justify-content-end">
                <input type="hidden" name="prodi" id="prodiInput" value="{{ request('prodi', 'D4') }}">
                <div class="col-auto">
                    <input type="text" name="search" id="searchInput" class="form-control form-control-sm"
                        placeholder="Cari nama atau NIM..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>

            <!-- Table Data Mahasiswa -->
            <div id="tableMahasiswa">
                @include('admin.kelola-akun.mahasiswa.crud-mahasiswa.read')
            </div>
        </div>
    </div>

    @include('admin.kelola-akun.mahasiswa.modal.edit')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editAkunMahasiswaModal');
            const form = document.getElementById('formEditMahasiswa');

            document.querySelectorAll('.btn-edit-mahasiswa').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = this.dataset.url;
                    const nama = this.dataset.nama;
                    const email = this.dataset.email;
                    const nim = this.dataset.nim;
                    const prodi = this.dataset.prodi;

                    // Set form action
                    form.action = url;

                    // Set form values
                    document.getElementById('edit_nama_mahasiswa').value = nama;
                    document.getElementById('edit_email_mahasiswa').value = email;
                    document.getElementById('edit_nim_mahasiswa').value = nim;
                    document.getElementById('edit_prodi_mahasiswa').value = prodi;

                    // Kosongkan password fields
                    document.getElementById('edit_password_mahasiswa').value = '';
                    document.getElementById('edit_password_confirmation_mahasiswa').value = '';
                });
            });
        });

        // Cek session success Laravel, lalu tampilkan alert
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
