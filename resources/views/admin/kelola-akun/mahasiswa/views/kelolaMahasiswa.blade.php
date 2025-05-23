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
            <!-- Tabs Jenjang -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request('jenjang') == null ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('jenjang') === 'D4' ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola', ['jenjang' => 'D4']) }}">D4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('jenjang') === 'D3' ? 'active' : '' }}"
                        href="{{ route('akun-mahasiswa.kelola', ['jenjang' => 'D3']) }}">D3</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form method="GET" action="{{ route('akun-mahasiswa.kelola') }}" id="searchForm"
                class="row g-2 mb-3 justify-content-end">
                <input type="hidden" name="jenjang" id="jenjangInput" value="{{ request('jenjang', 'D4') }}">
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
            const searchInput = document.getElementById('searchInput');
            const jenjangInput = document.getElementById('jenjangInput');
            const tableContainer = document.getElementById('tableMahasiswa');

            searchInput.addEventListener('keyup', function() {
                const search = this.value;
                const jenjang = jenjangInput.value;

                const url =
                    `{{ route('akun-mahasiswa.kelola') }}?search=${encodeURIComponent(search)}&jenjang=${encodeURIComponent(jenjang)}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                    })
                    .catch(error => console.error(error));
            });
        });
    </script>
@endpush
