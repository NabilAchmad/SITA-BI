@push('styles')
    <style>
        /* Blur + dark overlay di belakang modal hanya saat modal aktif */
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.3);
            /* sedikit lebih transparan */
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* Modal content lebih cerah dan soft */
        #tambahAkunModal .modal-content {
            background-color: rgba(245, 245, 245, 0.95);
            /* abu sangat terang, hampir putih */
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            color: #212529;
            /* teks gelap */
        }

        /* Modal header border bawah lembut */
        #tambahAkunModal .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Tombol close default hitam */
        #tambahAkunModal .btn-close.btn-close-white {
            filter: none;
        }

        /* Label form default gelap */
        #tambahAkunModal .form-floating>label {
            color: #495057;
            /* warna label abu gelap */
            transition: color 0.3s ease;
        }

        /* Input warna putih dengan border abu */
        #tambahAkunModal .form-control {
            background-color: #fff;
            border: 1px solid #ced4da;
            color: #212529;
            border-radius: 0.5rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Fokus input dengan border biru dan bayangan halus */
        #tambahAkunModal .form-control:focus {
            background-color: #fff;
            border-color: #3399ff;
            box-shadow: 0 0 6px #3399ff88;
            color: #212529;
        }

        /* Label saat input focus - ubah warna supaya tidak nabrak background */
        #tambahAkunModal .form-floating .form-control:focus~label {
            color: #3399ff;
            font-weight: 600;
        }

        /* Tombol submit warna biru yang lembut */
        #tambahAkunModal button[type="submit"] {
            background: #3399ff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            color: white;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px #3399ff88;
        }

        #tambahAkunModal button[type="submit"]:hover {
            background: #267acc;
            box-shadow: 0 6px 16px #267acccc;
        }
    </style>
@endpush

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">

        @include('admin.kelola-akun.breadcrumbs.navlink')

        {{-- Judul halaman --}}

        <h4 class="card-title text-primary mb-0">Kelola Akun Dosen</h4>
        <!-- Modal Trigger -->
        <button type="button" class="btn btn-success btn-sm  d-flex align-items-center gap-1 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#tambahAkunModal">
            <i class="bi bi-plus-lg fs-5"></i> Tambah Akun
        </button>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('akun-dosen.kelola') }}" class="row g-2 mb-3 justify-content-end">
            <div class="col-auto">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama dosen"
                    value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </form>

        <div class="table-responsive">

            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = ($dosenList->currentPage() - 1) * $dosenList->perPage() + 1;
                    @endphp

                    @forelse ($dosenList as $dosen)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $dosen->user->name }}</td>
                            <td>{{ $dosen->user->email }}</td>
                            <td>
                                @foreach ($dosen->user->roles as $role)
                                    <span class="badge bg-primary">{{ ucfirst($role->nama_role) }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{-- Tombol Edit --}}
                                <button class="btn-edit btn btn-warning btn-xs me-1" data-id="{{ $dosen->id }}"
                                    data-url="{{ url('admin/kelola-akun/dosen/update/' . $dosen->id) }}"
                                    data-nama="{{ $dosen->user->name }}" data-email="{{ $dosen->user->email }}"
                                    data-nidn="{{ $dosen->nidn }}" data-bs-toggle="modal"
                                    data-bs-target="#editAkunModal">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                {{-- Detail --}}
                                <button type="button" class="btn btn-info btn-xs btn-detail"
                                    data-nama="{{ $dosen->user->name }}" data-email="{{ $dosen->user->email }}"
                                    data-nidn="{{ $dosen->nidn }}" data-foto="{{ $dosen->user->photo }}"
                                    data-created="{{ $dosen->created_at }}" data-updated="{{ $dosen->updated_at }}"
                                    data-roles="{{ json_encode($dosen->user->roles) }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>

                                <!-- Tombol hapus -->
                                <button type="button" class="btn btn-danger btn-xs btn-hapus"
                                    data-id="{{ $dosen->id }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $dosenList->withQueryString()->links() }}
        </div>
    </div>
</div>

@include('admin.kelola-akun.dosen.modal.detail')
@include('admin.kelola-akun.dosen.modal.tambah')
@include('admin.kelola-akun.dosen.modal.edit')

@if ($errors->any())
    @php
        $uniqueErrors = collect($errors->all())->filter(function ($e) {
            return str_contains(strtolower($e), 'email') || str_contains(strtolower($e), 'nidn');
        });
    @endphp

    @if ($uniqueErrors->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: "Error!",
                    text: "{{ $uniqueErrors->first() }}",
                    icon: "error",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-danger"
                        }
                    }
                });
            });
        </script>
    @endif
@endif
