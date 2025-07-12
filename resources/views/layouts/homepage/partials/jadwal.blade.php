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