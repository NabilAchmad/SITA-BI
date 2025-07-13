<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light py-5 position-relative">
    
    <a href="{{ route('mahasiswa.sidang.dashboard') }}" class="btn position-absolute top-0 end-0 mt-3 me-3 z-3"
       style="background-color: transparent; color: inherit; border: 2px solid #00050b; box-shadow: none; border-radius: 20px; padding: 0.375rem 0.75rem;">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Dashboard
    </a>

    <div class="card shadow-lg rounded-4 w-100" style="max-width: 1000px;">
        
        <div class="row g-0">
            <!-- Profil Mahasiswa -->
            <div
                class="col-md-4 bg-primary text-white text-center p-5 rounded-start-4 d-flex flex-column justify-content-center align-items-center">
                @php
                    $user = $sidang && $sidang->first() ? $sidang->first()->tugasAkhir->mahasiswa->user : null;
                    $photoUrl = $user && $user->photo ? asset('storage/' . $user->photo) : asset('assets/img/placeholder.png');
                @endphp
                <img src="{{ $photoUrl }}" class="rounded-circle mb-4 border border-white shadow mx-auto"
                    alt="Foto Mahasiswa" style="width: 150px; height: 150px; object-fit: cover;">
                <h4 class="fw-semibold mb-1">
                    {{ $sidang && $sidang->first() ? $sidang->first()->tugasAkhir->mahasiswa->user->name : 'Nama Mahasiswa' }}
                </h4>
                <p class="fs-5 mb-0">NIM:
                    {{ $sidang && $sidang->first() ? $sidang->first()->tugasAkhir->mahasiswa->nim : '-' }}
                </p>
            </div>

            <!-- Informasi Sidang -->
            <div class="col-md-8 p-5 position-relative">
                <h5 class="fw-bold mb-3 text-secondary">Nilai Sidang Akhir</h5>

                @if($sidang && $sidang->count() > 0)
                    @foreach($sidang as $s)
                        <div class="mb-4">
                            <h6 class="fw-bold">Judul Sidang:</h6>
                            <p>{{ $s->tugasAkhir->judul ?? '-' }}</p>

                            <div class="row mb-2">
                                <div class="col-sm-5 fw-semibold">Tanggal Sidang</div>
                                <div class="col-sm-7">{{ $s->tanggal_sidang ? $s->tanggal_sidang->format('d F Y') : '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-5 fw-semibold">Dosen Pembimbing</div>
                                <div class="col-sm-7">
                                    @if($s->tugasAkhir && $s->tugasAkhir->dosenPembimbing)
                                        {{ $s->tugasAkhir->dosenPembimbing->pluck('user.name')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-5 fw-semibold">Dosen Penguji</div>
                                <div class="col-sm-7">
                                    @php
                                        $pengujiNames = $s->nilaiSidang->pluck('dosen.user.name')->unique()->join(', ');
                                    @endphp
                                    {{ $pengujiNames ?: '-' }}
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="fw-bold text-secondary mb-2">Rincian Nilai</h6>
                                @if($s->nilaiSidang->count() > 0)
                                    <ul class="list-unstyled ms-3 fs-6">
                                        @foreach($s->nilaiSidang as $nilai)
                                            <li>
                                                <i class="bi bi-person-square me-2 text-primary"></i>
                                                {{ $nilai->dosen->user->name ?? 'Dosen' }} - {{ $nilai->aspek }}:
                                                <strong>{{ $nilai->skor }}</strong>
                                                @if($nilai->komentar)
                                                    <br><small class="text-muted">Komentar: {{ $nilai->komentar }}</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="alert alert-warning">
                                        Nilai belum ada, silakan cek kembali nanti.
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <h6 class="fw-bold text-secondary mb-3">Status Sidang</h6>
                                @php
                                    $status = $s->status ?? 'Belum ada status';
                                    $badgeClass = 'bg-secondary';
                                    if (stripos($status, 'lulus') !== false) {
                                        $badgeClass = 'bg-success';
                                    } elseif (stripos($status, 'revisi') !== false) {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif (stripos($status, 'tidak lulus') !== false) {
                                        $badgeClass = 'bg-danger';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-4 py-3 rounded-pill d-inline-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i> {{ ucfirst($status) }}
                                </span>
                            </div>

                            <div class="text-center mt-4">
                                <h1 class="display-4 fw-bold text-primary">
                                    @php
                                        $average = $s->nilaiSidang->avg('skor');
                                    @endphp
                                    {{ $average ? number_format($average, 2) : '-' }}
                                </h1>
                                <p class="text-muted">Nilai Akhir Sidang</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-warning">
                        Nilai sidang belum tersedia. Silakan cek kembali nanti.
                    </div>
                @endif

                
            </div>
        </div>
    </div>
</div>
