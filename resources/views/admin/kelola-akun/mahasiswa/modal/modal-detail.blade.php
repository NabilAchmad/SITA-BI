<!-- Modal Detail -->
<div class="modal fade" id="detailMahasiswaModal{{ $mhs->id }}" tabindex="-1"
    aria-labelledby="detailMahasiswaLabel{{ $mhs->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="detailMahasiswaLabel{{ $mhs->id }}">Detail Mahasiswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body text-start">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img src="{{ $mhs->user->foto ?? asset('assets/img/default-user.png') }}"
                            class="img-thumbnail rounded" alt="Foto Mahasiswa" style="max-width: 150px;">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Nama:</strong> {{ $mhs->user->name }}</p>
                        <p><strong>Email:</strong> {{ $mhs->user->email }}</p>
                        <p><strong>NIM:</strong> {{ $mhs->nim }}</p>
                        @php
                            $prodi = $mhs->prodi;
                            if (strtolower($prodi) === 'd4') {
                                $prodi = 'D4 Bahasa Inggris';
                            } elseif (strtolower($prodi) === 'd3') {
                                $prodi = 'D3 Bahasa Inggris';
                            }
                        @endphp
                        <p><strong>Program Studi:</strong> {{ $prodi }}</p>

                        @if ($mhs->tugasAkhir)
                            <p><strong>Judul Tugas Akhir:</strong> {{ $mhs->tugasAkhir->judul }}</p>
                            @php
                                $pembimbing1 = $mhs->tugasAkhir->peranDosenTa->firstWhere('peran', 'pembimbing1');
                                $pembimbing2 = $mhs->tugasAkhir->peranDosenTa->firstWhere('peran', 'pembimbing2');
                            @endphp

                            <p><strong>Pembimbing 1:</strong> {{ $pembimbing1?->dosen->user->name ?? '-' }}</p>
                            <p><strong>Pembimbing 2:</strong> {{ $pembimbing2?->dosen->user->name ?? '-' }}</p>
                        @else
                            <p><strong>Tugas Akhir:</strong> Belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
