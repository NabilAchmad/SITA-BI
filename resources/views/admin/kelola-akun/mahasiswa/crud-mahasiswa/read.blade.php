<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + 1;
            @endphp
            @forelse ($mahasiswa as $mhs)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="text-start">{{ $mhs->user->name }}</td>
                    <td>{{ $mhs->user->email }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        @if ($mhs->prodi === 'd4')
                            D4 Bahasa Inggris
                        @elseif ($mhs->prodi === 'd3')
                            D3 Bahasa Inggris
                        @else
                            {{ $mhs->prodi }}
                        @endif
                    </td>
                    <td>
                        <button class="btn-edit-mahasiswa btn btn-warning btn-xs me-1" data-id="{{ $mhs->id }}"
                            data-url="{{ url('admin/kelola-akun/mahasiswa/update/' . $mhs->id) }}"
                            data-nama="{{ $mhs->user->name }}" data-email="{{ $mhs->user->email }}"
                            data-nim="{{ $mhs->nim }}" data-prodi="{{ $mhs->prodi }}" data-bs-toggle="modal"
                            data-bs-target="#editAkunMahasiswaModal">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        
                        <button class="btn btn-info btn-xs" data-bs-toggle="modal"
                            data-bs-target="#detailMahasiswaModal{{ $mhs->id }}">
                            <i class="bi bi-info-circle"></i> Detail
                        </button>

                        @include('admin.kelola-akun.mahasiswa.modal.modal-detail', ['mhs' => $mhs])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $mahasiswa->appends(request()->query())->links() }}
</div>
