<section id="jadwal" class="pengumuman section pt-4">

    <!-- Section Title -->
    <div class="container section-title text-center" data-aos="fade-up" style="margin-bottom: 30px;">
        <h1>Jadwal Sidang</h1>
    </div>

    <div class="container">
        <div class="row justify-content-center gy-4">

            @forelse ($jadwalSidangAkhir as $jadwal)
                <div class="col-12 col-md-10 col-lg-8 mb-4" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow"
                        style="background-color: #f0f8ff; border-radius: 18px; transition: 0.3s ease;">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="card-title mb-3"
                                style="color: #0d6efd; font-weight: 600; font-size: 1.5rem;">
                                {{ $jadwal->sidang->mahasiswa->nama ?? 'Nama Mahasiswa' }}
                            </h5>
                            <p class="card-text" style="color: #333; font-size: 16px; line-height: 1.6;">
                                <strong>Topik:</strong> {{ $jadwal->sidang->judul ?? 'Judul Tugas Akhir' }}<br>
                                <strong>Waktu:</strong>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }} -
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB<br>
                                <strong>Ruang:</strong> {{ $jadwal->ruangan->nama ?? 'Ruang' }}<br>
                                <strong>Dosen:</strong> {{ $jadwal->sidang->dosen->nama ?? 'Nama Dosen' }}
                            </p>
                        </div>
                        <div class="card-footer border-0 bg-transparent text-end px-4 pb-3"
                            style="color: #0d6efd; font-size: 14px;">
                            Oleh: {{ $jadwal->sidang->admin->name ?? 'Admin' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-mortarboard fs-1 mb-3 d-block"></i>
                    <p class="mb-0">Belum ada Jadwal untuk ditampilkan.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>
