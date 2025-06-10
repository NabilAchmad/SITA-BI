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
                        <th>Jabatan</th>
                        <th>Jumlah Bimbingan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                @php
                    // Ambil status bimbingan terakhir dosen 1 dan dosen 2 (null jika belum ada)
                    $statusDospem1 = $statusBimbingan[$dospem1->id] ?? null;
                    $statusDospem2 = $statusBimbingan[$dospem2->id] ?? null;
                @endphp

                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($dosenList as $dosen)
                        @php
                            // Jumlah bimbingan per dosen
                            $jumlahBimbingan = $bimbinganCount[$dosen->dosen_id] ?? 0;

                            // Default tombol tidak disable
                            $disabled = false;

                            if ($dosen->peran === 'pembimbing1') {
                                // Tombol dospem1 disable jika sudah diajukan tapi belum disetujui
                                if ($statusDospem1 !== null && $statusDospem1 !== 'disetujui') {
                                    $disabled = true;
                                }
                            } elseif ($dosen->peran === 'pembimbing2') {
                                // Tombol dospem2 disable kalau:
                                // - dospem1 belum disetujui (belum bisa lanjut ke dospem2)
                                // - dospem2 sudah diajukan tapi belum disetujui
                                if (
                                    $statusDospem1 !== 'disetujui' ||
                                    ($statusDospem2 !== null && $statusDospem2 !== 'disetujui')
                                ) {
                                    $disabled = true;
                                }
                            }
                        @endphp

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $dosen->dosen->user->name ?? '-' }}</td>
                            <td>
                                @if ($dosen->peran === 'pembimbing1')
                                    <span class="badge bg-info">Dosen Pembimbing 1</span>
                                @elseif ($dosen->peran === 'pembimbing2')
                                    <span class="badge bg-secondary">Dosen Pembimbing 2</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $jumlahBimbingan }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalAjukanJadwal" data-dosenid="{{ $dosen->dosen_id }}"
                                    data-peran="{{ $dosen->peran }}" {{ $disabled ? 'disabled' : '' }}>
                                    <i class="bi bi-calendar-plus"></i> Ajukan Jadwal {{ ucfirst($dosen->peran) }}
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
    </div>
</div>

@include('mahasiswa.bimbingan.modal.ajukan')

@push('scripts')
    <script>
        const modalAjukan = document.getElementById('modalAjukanJadwal');

        modalAjukan.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const dosenId = button.getAttribute('data-dosenid');
            const peran = button.getAttribute('data-peran');

            modalAjukan.querySelector('#modal_dosen_id').value = dosenId;
            modalAjukan.querySelector('#modal_tipe_dospem').value = peran === 'pembimbing1' ? 1 : 2;
            modalAjukan.querySelector('#modal_label_peran').textContent = peran.replace('pembimbing',
                'Dosen Pembimbing ');
        });
    </script>

    {{-- Tempatkan di bagian paling bawah atau di section scripts --}}
    @if (session('success'))
        <script>
            swal({
                title: "Jadwal Berhasil Diajukan!",
                text: "{{ session('success') }}",
                icon: "success",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-primary"
                    }
                }
            });
        </script>
    @endif

    @if ($errors->has('error'))
        <script>
            swal({
                title: "Gagal!",
                text: "{{ $errors->first('error') }}",
                icon: "error",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-danger"
                    }
                }
            });
        </script>
    @endif

@endpush

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
