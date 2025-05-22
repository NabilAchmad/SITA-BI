<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary">Kelola Akun Dosen</h1>
</div>

<div class="mb-3 text-end">
    <a href="{{ route('akun-dosen.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah Akun
    </a>
</div>

<div class="mb-3">
    <form method="GET" action="{{ route('akun-dosen.kelola') }}" class="d-flex justify-content-end">
        <input type="text" name="search" wire:model="search" class="form-control w-auto me-2" placeholder="Cari nama dosen"
            value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-search me-1"></i> Cari
        </button>
    </form>
</div>

<div class="table-responsive">
    <table id="dosenTable" class="table table-striped table-bordered table-hover text-center align-middle shadow-sm">
        <thead class="table-dark">
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dosenList as $index => $dosen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dosen->user->name }}</td>
                    <td>{{ $dosen->user->email }}</td>
                    <td>
                        @foreach ($dosen->user->roles as $role)
                            <span class="badge bg-primary">{{ ucfirst($role->nama_role) }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('akun-dosen.edit', $dosen->id) }}" class="btn btn-warning btn-sm me-1"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('akun-dosen.destroy', $dosen->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $dosenList->withQueryString()->links() }}
</div>
