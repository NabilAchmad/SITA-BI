<section id="jadwal" class="values section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>Jadwal Mahasiswa</h1>
    </div><!-- End Section Title -->

    <div class="container">
        <div class="row gy-4">
            <div class="col-12">
                <!-- Tab Menu -->
                <ul class="nav nav-pills mb-3 justify-content-center" id="tabMenu">
                    <li class="nav-item">
                        <a class="nav-link active" id="sidang-tab" href="#"
                            onclick="showContent(event, 'sidang')">Sidang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="seminar-tab" href="#"
                            onclick="showContent(event, 'seminar')">Seminar</a>
                    </li>
                </ul>

                <!-- SIDANG -->
                <div id="sidang" class="tab-content">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @forelse ($jadwalSidangAkhir as $jadwal)
                            <div class="col">
                                <div class="card border-primary shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">{{ $jadwal->sidang->mahasiswa->nama ?? 'Nama Mahasiswa' }}</h5>
                                        <p class="card-text">
                                            <strong>Topik:</strong> {{ $jadwal->sidang->judul ?? 'Judul Sidang' }}<br>
                                            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB<br>
                                            <strong>Ruang:</strong> {{ $jadwal->ruangan->nama ?? 'Ruang' }}<br>
                                            <strong>Dosen:</strong> {{ $jadwal->sidang->dosen_pembimbing->nama ?? 'Dosen Pembimbing' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">Belum ada jadwal sidang yang dijadwalkan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- SEMINAR -->
                <div id="seminar" class="tab-content d-none">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                            <div class="card border-success shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Dinda Rahmawati</h5>
                                    <p class="card-text">
                                        <strong>Topik:</strong> Game Edukasi untuk Anak Usia Dini<br>
                                        <strong>Waktu:</strong> Kamis, 25 April 2025 - 13:00 WIB<br>
                                        <strong>Ruang:</strong> B102<br>
                                        <strong>Dosen:</strong> Dr. Andi Nugroho, M.Pd
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-success shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Rahmat Aditya</h5>
                                    <p class="card-text">
                                        <strong>Topik:</strong> UI/UX Design untuk Mobile Banking<br>
                                        <strong>Waktu:</strong> Jumat, 26 April 2025 - 09:00 WIB<br>
                                        <strong>Ruang:</strong> B103<br>
                                        <strong>Dosen:</strong> Dr. Sinta Marlina
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script untuk tab -->
        <script>
            function showContent(event, id) {
                event.preventDefault();

                // Sembunyikan semua konten
                document.getElementById('sidang').classList.add('d-none');
                document.getElementById('seminar').classList.add('d-none');

                // Reset active tab
                document.getElementById('sidang-tab').classList.remove('active');
                document.getElementById('seminar-tab').classList.remove('active');

                // Tampilkan konten dan aktifkan tab
                document.getElementById(id).classList.remove('d-none');
                document.getElementById(id + '-tab').classList.add('active');
            }
        </script>

    </div>

</section>