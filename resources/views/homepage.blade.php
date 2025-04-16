<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SITA-BI Homepage</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="index-page">

    <!-- Header -->
    @include('layouts/header')
    <main class="main">

        <!-- Wellcome section -->
        <section id="hero" class="hero section">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <p class="fs-5 mb-2">Hello, Welcome</p>
                        <h1 data-aos="fade-up">Your ultimate solution for managing English Department thesis projects.
                        </h1>
                        <p data-aos="fade-up" style="color: #ffb01e" data-aos-delay="100">Stay organized, stay on
                            track, and achieve your academic goals with ease.</p>
                        <div class="d-flex gap-4">
                            <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
                                <a href="#about" class="btn btn-primary text-white px-4 py-2 rounded">Login<i
                                        class=""></i></a>
                            </div>
                            <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
                                <a href="#about" class="btn btn-outline-primary px-4 py-2 rounded">Register<i
                                        class=""></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                        <img src="assets/img/Illustration_2.png" class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Tawaran Topik Section -->
        <section id="tawarantopik" class="tawrantopik section">

            <div class="container" data-aos="fade-up">
                <!-- judul -->
                <div class="section-title mt-3">
                    <h1>Tawaran Topik</h1>
                </div>

                {{-- Card Tawaran Topik --}}
                <div class="card mb-4 bg-white shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">Pengembangan Media Pembelajaran Berbasis Game</h5>
                        <p class="card-text text-dark">
                            Tugas akhir ini bertujuan merancang dan mengembangkan media pembelajaran interaktif berbasis
                            game guna meningkatkan motivasi dan pemahaman siswa pada materi tertentu. Melalui pendekatan
                            game-based learning, mahasiswa akan melakukan analisis kebutuhan, perancangan desain,
                            pengembangan prototipe, serta evaluasi efektivitas media tersebut. Metode pengembangan dapat
                            menggunakan kerangka kerja seperti ADDIE (Analysis, Design, Development, Implementation,
                            Evaluation) atau Game Development Life Cycle. Hasil akhir berupa game edukasi yang diuji
                            kelayakannya melalui penilaian ahli dan pengguna (siswa/guru).
                        </p>
                        <p class="card-text text-secondary d-flex justify-content-between align-items-center">
                            <span><strong>Dosen Pembimbing:</strong> Dr. Andi Nugroho, M.Pd</span>
                            <span class="badge bg-primary">Kuota Tersisa: 2</span>
                        </p>
                    </div>
                </div>

                <div class="card mb-4 bg-white shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">Pengembangan Media Pembelajaran Berbasis Game</h5>
                        <p class="card-text text-dark">
                            Tugas akhir ini bertujuan merancang dan mengembangkan media pembelajaran interaktif berbasis
                            game guna meningkatkan motivasi dan pemahaman siswa pada materi tertentu. Melalui pendekatan
                            game-based learning, mahasiswa akan melakukan analisis kebutuhan, perancangan desain,
                            pengembangan prototipe, serta evaluasi efektivitas media tersebut. Metode pengembangan dapat
                            menggunakan kerangka kerja seperti ADDIE (Analysis, Design, Development, Implementation,
                            Evaluation) atau Game Development Life Cycle. Hasil akhir berupa game edukasi yang diuji
                            kelayakannya melalui penilaian ahli dan pengguna (siswa/guru).
                        </p>
                        <p class="card-text text-secondary d-flex justify-content-between align-items-center">
                            <span><strong>Dosen Pembimbing:</strong> Dr. Andi Nugroho, M.Pd</span>
                            <span class="badge bg-primary">Kuota Tersisa: 2</span>
                        </p>
                    </div>
                </div>
                <div class="card mb-4 bg-white shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">Pengembangan Media Pembelajaran Berbasis Game</h5>
                        <p class="card-text text-dark">
                            Tugas akhir ini bertujuan merancang dan mengembangkan media pembelajaran interaktif berbasis
                            game guna meningkatkan motivasi dan pemahaman siswa pada materi tertentu. Melalui pendekatan
                            game-based learning, mahasiswa akan melakukan analisis kebutuhan, perancangan desain,
                            pengembangan prototipe, serta evaluasi efektivitas media tersebut. Metode pengembangan dapat
                            menggunakan kerangka kerja seperti ADDIE (Analysis, Design, Development, Implementation,
                            Evaluation) atau Game Development Life Cycle. Hasil akhir berupa game edukasi yang diuji
                            kelayakannya melalui penilaian ahli dan pengguna (siswa/guru).
                        </p>
                        <p class="card-text text-secondary d-flex justify-content-between align-items-center">
                            <span><strong>Dosen Pembimbing:</strong> Dr. Andi Nugroho, M.Pd</span>
                            <span class="badge bg-primary">Kuota Tersisa: 2</span>
                        </p>
                    </div>
                </div>
            </div>
        </section><!-- /Tawaran Topik  Section -->

        <!-- Jadwal Section -->
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
                                <div class="col">
                                    <div class="card border-primary shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Erlan Nugroho</h5>
                                            <p class="card-text">
                                                <strong>Topik:</strong> Sistem Informasi Akademik Terintegrasi<br>
                                                <strong>Waktu:</strong> Senin, 22 April 2025 - 10:00 WIB<br>
                                                <strong>Ruang:</strong> A201<br>
                                                <strong>Dosen:</strong> Dr. Andi Nugroho, M.Pd
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card border-primary shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Rina Kusuma</h5>
                                            <p class="card-text">
                                                <strong>Topik:</strong> Aplikasi Manajemen Perpustakaan Digital<br>
                                                <strong>Waktu:</strong> Senin, 22 April 2025 - 11:30 WIB<br>
                                                <strong>Ruang:</strong> A202<br>
                                                <strong>Dosen:</strong> Dr. Budi Santosa
                                            </p>
                                        </div>
                                    </div>
                                </div>
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

        </section><!-- /Jadwal Section -->

        <!-- Pengumuman Section -->
        <section id="pengumuman" class="pengumuman section pt-4">
            <!-- Section Title -->
            <div class="container section-title text-center" data-aos="fade-up" style="margin-bottom: 30px;">
                <h1>Pengumuman</h1>
            </div>

            <div class="container">
                <div class="row justify-content-center gy-4">

                    <div class="col-12 col-md-10 col-lg-8" data-aos="fade-up" data-aos-delay="100">
                        <div class="card h-100 border-0 shadow"
                            style="background-color: #f0f8ff; border-radius: 18px; transition: 0.3s ease;">
                            <div class="card-body p-4 p-md-5">
                                <h5 class="card-title mb-3"
                                    style="color: #0d6efd; font-weight: 600; font-size: 1.5rem;">
                                    Judul Pengumuman
                                </h5>
                                <p class="card-text" style="color: #333; font-size: 16px; line-height: 1.6;">
                                    Ini adalah isi pengumuman yang menjelaskan detail penting kepada pembaca. Bisa
                                    berupa
                                    tanggal, kegiatan, atau info lainnya. Pastikan semua informasi tersampaikan secara
                                    jelas dan singkat.
                                </p>
                            </div>
                            <div class="card-footer border-0 bg-transparent text-end px-4 pb-3"
                                style="color: #0d6efd; font-size: 14px;">
                                Oleh: Admin
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <style>
            .card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            }

            @media (max-width: 576px) {
                .card-body {
                    padding: 1.5rem !important;
                }
            }
        </style>
        <!-- /Pengumuman Section -->

        <!-- Team Section -->
        <section id="team" class="team section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Team 7</h2>
                <p>Developer Team</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                        data-aos-delay="100">
                        <div class="team-member">
                            <div class="member-img">
                                <img src="assets/img/team/erland.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Erland Agsya Agustian</h4>
                                <span>Chief Executive Officer</span>
                                <p>Velit aut quia fugit et et. Dolorum ea voluptate vel tempore tenetur ipsa quae aut.
                                    Ipsum exercitationem iure minima enim corporis et voluptate.</p>
                            </div>
                        </div>
                    </div><!-- End Team Member -->

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="team-member">
                            <div class="member-img">
                                <img src="assets/img/team/nabil.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Nabil Achmad Khoir</h4>
                                <span>Product Manager</span>
                                <p>Quo esse repellendus quia id. Est eum et accusantium pariatur fugit nihil minima
                                    suscipit corporis. Voluptate sed quas reiciendis animi neque sapiente.</p>
                            </div>
                        </div>
                    </div><!-- End Team Member -->

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                        data-aos-delay="300">
                        <div class="team-member">
                            <div class="member-img">
                                <img src="assets/img/team/kasih.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Kasih Ananda Nardi</h4>
                                <span>CTO</span>
                                <p>Vero omnis enim consequatur. Voluptas consectetur unde qui molestiae deserunt.
                                    Voluptates enim aut architecto porro aspernatur molestiae modi.</p>
                            </div>
                        </div>
                    </div><!-- End Team Member -->

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                        data-aos-delay="400">
                        <div class="team-member">
                            <div class="member-img">
                                <img src="assets/img/team/gilang.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Gilang Dwi Yuwana</h4>
                                <span>Accountant</span>
                                <p>Rerum voluptate non adipisci animi distinctio et deserunt amet voluptas. Quia aut
                                    aliquid doloremque ut possimus ipsum officia.</p>
                            </div>
                        </div>
                    </div><!-- End Team Member -->

                </div>

            </div>

        </section><!-- /Team Section -->
    </main>

    @include('layouts/footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
