<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        @include('mahasiswa.bimbingan.breadcrumbs.navlink')
        <h4 class="card-title text-primary mb-0">Ajukan Jadwal Bimbingan</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Dospem 1</th>
                        <th>Dospem 2</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = ($dosenList->currentPage() - 1) * $dosenList->perPage() + 1; @endphp
                    @forelse ($dosenList as $dosen)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $dosen->user->name }}</td>
                            <td>{{ $dosen->user->email }}</td>
                            <td>
                                @php $count1 = $dosen->mahasiswaDospem1->count() ?? 0; @endphp
                                {!! $count1
                                    ? '<span class="badge bg-info">' . $count1 . ' Mahasiswa</span>'
                                    : '<span class="text-muted">-</span>' !!}
                            </td>
                            <td>
                                @php $count2 = $dosen->mahasiswaDospem2->count() ?? 0; @endphp
                                {!! $count2
                                    ? '<span class="badge bg-secondary">' . $count2 . ' Mahasiswa</span>'
                                    : '<span class="text-muted">-</span>' !!}
                            </td>
                            <td>
                                @foreach ($dosen->user->roles as $role)
                                    <span class="badge bg-primary">{{ ucfirst($role->nama_role) }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{-- Form pengajuan dospem 1 --}}
                                <form action="{{ route('bimbingan.store') }}" method="POST" class="mb-1">
                                    @csrf
                                    <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                                    <input type="hidden" name="tipe_dospem" value="1">
                                    <button class="btn btn-success btn-sm w-100" type="submit"
                                        {{ isset($dospem1) ? 'disabled' : '' }}>
                                        <i class="bi bi-calendar-plus"></i> Ajukan Dospem 1
                                    </button>
                                </form>

                                {{-- Form pengajuan dospem 2 --}}
                                <form action="{{ route('bimbingan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                                    <input type="hidden" name="tipe_dospem" value="2">
                                    <button class="btn btn-secondary btn-sm w-100" type="submit"
                                        {{ isset($dospem2) ? 'disabled' : '' }}>
                                        <i class="bi bi-calendar-plus"></i> Ajukan Dospem 2
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Data tidak ditemukan.</td>
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

{{-- Validasi error --}}
@if ($errors->any())
    @php
        $uniqueErrors = collect($errors->all())->filter(
            fn($e) => str_contains(strtolower($e), 'email') || str_contains(strtolower($e), 'nidn'),
        );
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
