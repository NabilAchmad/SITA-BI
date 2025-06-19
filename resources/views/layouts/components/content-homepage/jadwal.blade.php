<!-- Section: Jadwal Mahasiswa -->
<section id="jadwal" class="values section">

    <!-- Section Title -->
    <div class="container section-title text-center" data-aos="fade-up">
        <h1 class="fw-bold mb-4">Agenda Sidang dan Seminar Mahasiswa</h1>
    </div>

    <div class="container">
        <div class="row gy-4">
            <div class="col-12">
                <!-- Tab Navigation -->
                <ul class="nav nav-pills mb-4 justify-content-center gap-3" id="tabMenu">
                    <li class="nav-item">
                        <a class="nav-link active px-4 py-2 rounded-pill" id="sidang-tab" href="#" 
                            onclick="showContent(event, 'sidang')">Sidang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 rounded-pill" id="seminar-tab" href="#" 
                            onclick="showContent(event, 'seminar')">Seminar</a>
                    </li>
                </ul>

                <!-- SIDANG Content -->
                <div id="sidang" class="tab-content">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col" data-aos="fade-up" data-aos-delay="100">
                            <div class="card border-primary shadow-sm h-100 hover-shadow">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-primary fw-bold mb-3">Erlan Nugroho</h5>
                                    <p class="card-text">
                                        <i class="bi bi-bookmark-fill me-2"></i><strong>Topik:</strong> Sistem Informasi Akademik Terintegrasi<br>
                                        <i class="bi bi-clock-fill me-2"></i><strong>Waktu:</strong> Senin, 22 April 2025 - 10:00 WIB<br>
                                        <i class="bi bi-geo-alt-fill me-2"></i><strong>Ruang:</strong> A201<br>
                                        <i class="bi bi-person-fill me-2"></i><strong>Dosen:</strong> Dr. Andi Nugroho, M.Pd
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col" data-aos="fade-up" data-aos-delay="200">
                            <div class="card border-primary shadow-sm h-100 hover-shadow">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-primary fw-bold mb-3">Rina Kusuma</h5>
                                    <p class="card-text">
                                        <i class="bi bi-bookmark-fill me-2"></i><strong>Topik:</strong> Aplikasi Manajemen Perpustakaan Digital<br>
                                        <i class="bi bi-clock-fill me-2"></i><strong>Waktu:</strong> Senin, 22 April 2025 - 11:30 WIB<br>
                                        <i class="bi bi-geo-alt-fill me-2"></i><strong>Ruang:</strong> A202<br>
                                        <i class="bi bi-person-fill me-2"></i><strong>Dosen:</strong> Dr. Budi Santosa
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEMINAR Content -->
                <div id="seminar" class="tab-content d-none">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col" data-aos="fade-up" data-aos-delay="100">
                            <div class="card border-success shadow-sm h-100 hover-shadow">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-success fw-bold mb-3">Dinda Rahmawati</h5>
                                    <p class="card-text">
                                        <i class="bi bi-bookmark-fill me-2"></i><strong>Topik:</strong> Game Edukasi untuk Anak Usia Dini<br>
                                        <i class="bi bi-clock-fill me-2"></i><strong>Waktu:</strong> Kamis, 25 April 2025 - 13:00 WIB<br>
                                        <i class="bi bi-geo-alt-fill me-2"></i><strong>Ruang:</strong> B102<br>
                                        <i class="bi bi-person-fill me-2"></i><strong>Dosen:</strong> Dr. Andi Nugroho, M.Pd
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col" data-aos="fade-up" data-aos-delay="200">
                            <div class="card border-success shadow-sm h-100 hover-shadow">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-success fw-bold mb-3">Rahmat Aditya</h5>
                                    <p class="card-text">
                                        <i class="bi bi-bookmark-fill me-2"></i><strong>Topik:</strong> UI/UX Design untuk Mobile Banking<br>
                                        <i class="bi bi-clock-fill me-2"></i><strong>Waktu:</strong> Jumat, 26 April 2025 - 09:00 WIB<br>
                                        <i class="bi bi-geo-alt-fill me-2"></i><strong>Ruang:</strong> B103<br>
                                        <i class="bi bi-person-fill me-2"></i><strong>Dosen:</strong> Dr. Sinta Marlina
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab functionality script -->
        <script>
            function showContent(event, id) {
                event.preventDefault();
                
                // Hide all content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('d-none');
                });

                // Reset active tabs
                document.querySelectorAll('.nav-link').forEach(tab => {
                    tab.classList.remove('active');
                });

                // Show selected content and activate tab
                document.getElementById(id).classList.remove('d-none');
                document.getElementById(id + '-tab').classList.add('active');
            }
        </script>

    </div>

</section>