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
                        <th>Nama Dosen</th>
                        <th>Peran</th>
                        <th>Jumlah Bimbingan</th>
                        <th>Status Bimbingan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($dosenList as $dosen)
                        @php
                            $dosenId = $dosen->dosen_id;
                            $jumlahBimbingan = $bimbinganCount[$dosenId] ?? 0;
                            $status = $statusBimbingan[$dosenId] ?? '-';
                            $disabled = $disabledPengajuan[$dosenId] ?? true;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $dosen->dosen->user->name ?? '-' }}</td>
                            <td>
                                @if ($dosen->peran === 'pembimbing1')
                                    <span class="badge bg-info">Pembimbing 1</span>
                                @elseif ($dosen->peran === 'pembimbing2')
                                    <span class="badge bg-secondary">Pembimbing 2</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $jumlahBimbingan }} / 9</td>
                            <td>
                                @if ($status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif ($status === 'diajukan')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif ($status === 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif (is_null($status))
                                    <span class="text-muted">Belum Ada</span>
                                @else
                                    <span class="text-muted">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalAjukanJadwal" data-dosenid="{{ $dosen->dosen_id }}"
                                    data-peran="{{ $dosen->peran }}" {{ $disabled ? 'disabled' : '' }}>
                                    <i class="bi bi-calendar-plus"></i> Ajukan Jadwal
                                </button>
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
    </div>
</div>

@include('mahasiswa.bimbingan.modal.ajukan')

@push('scripts')
    <script>
        const modalAjukan = document.getElementById('modalAjukanJadwal');

        if (modalAjukan) {
            modalAjukan.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const dosenId = button.getAttribute('data-dosenid');
                const peran = button.getAttribute('data-peran');

                modalAjukan.querySelector('#modal_dosen_id').value = dosenId;
                modalAjukan.querySelector('#modal_tipe_dospem').value = peran === 'pembimbing1' ? 1 : 2;
                modalAjukan.querySelector('#modal_label_peran').textContent = peran.replace('pembimbing',
                    'Dosen Pembimbing ');
            });
        }

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

        @if (session('error'))
            swal({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-danger"
                    }
                }
            });
        @endif
    </script>
@endpush
