<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="card shadow-lg rounded-4 w-100" style="max-width: 1000px;">
        <div class="row g-0">
            <!-- Profil Mahasiswa -->
            <div
                class="col-md-4 bg-primary text-white text-center p-5 rounded-start-4 d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('assets/img/team/kasih.jpg') }}" class="rounded-circle mb-4 border border-white shadow mx-auto"
                    alt="Foto Mahasiswa" style="width: 150px; height: 150px; object-fit: cover;">
                <h4 class="fw-semibold mb-1">Kasih Ananda Nardi</h4>
                <p class="fs-5 mb-0">NIM: 12345678</p>
            </div>


            <!-- Informasi Sidang -->
            <div class="col-md-8 p-5">
                <!-- Tabs -->
                <ul class="nav nav-tabs justify-content-center mb-4 fs-5" id="nilaiSidangTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-4" id="sempro-tab" data-bs-toggle="tab"
                            data-bs-target="#sempro" type="button" role="tab">
                            Sidang Sempro
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4" id="akhir-tab" data-bs-toggle="tab" data-bs-target="#akhir"
                            type="button" role="tab">
                            Sidang Akhir
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="nilaiSidangTabContent">
                    <!-- Sidang Sempro -->
                    <div class="tab-pane fade show active" id="sempro" role="tabpanel">
                        <h5 class="fw-bold mb-3 text-secondary">Judul Sidang</h5>
                        <p class="fs-5 text-muted mb-4">Sistem Informasi Akademik</p>

                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Tanggal Sidang</div>
                            <div class="col-sm-7">10 April 2025</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Dosen Pembimbing</div>
                            <div class="col-sm-7">Dr. Budi Santoso, M.Kom</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Dosen Penguji</div>
                            <div class="col-sm-7">Prof. Sari Dewi, M.T. dan Dr. Agus Salim, M.Kom,
                                 Dr. Wawan Setiawan, M.Kom, Dr. Lina Marlina, M.Kom</div>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold text-secondary mb-2">Rincian Nilai</h6>
                            <ul class="list-unstyled ms-3 fs-6">
                                <li><i class="bi bi-file-earmark-text me-2 text-primary"></i> Kelengkapan Dokumen:
                                    <strong>20</strong></li>
                                <li><i class="bi bi-journal-text me-2 text-primary"></i> Pemahaman Materi:
                                    <strong>30</strong></li>
                                <li><i class="bi bi-easel me-2 text-primary"></i> Penyajian Sidang: <strong>25</strong>
                                </li>
                                <li><i class="bi bi-chat-left-dots me-2 text-primary"></i> Tanya Jawab:
                                    <strong>20</strong></li>
                            </ul>
                        </div>
                        
                        <!-- Nilai Per Dosen Penguji -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-secondary mb-2">Nilai dari Masing-masing Dosen Penguji</h6>
                            <ul class="list-unstyled ms-3 fs-6">
                                <li><i class="bi bi-person-square me-2 text-primary"></i> Prof. Sari Dewi, M.T.: <strong>94</strong></li>
                                <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Agus Salim, M.Kom: <strong>96</strong></li>
                                <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Wawan Setiawan, M.Kom: <strong>93</strong></li>
                                <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Lina Marlina, M.Kom: <strong>97</strong></li>
                            </ul>
                        </div>

                        <!-- Status Sidang -->
                        <div class="mt-3">
                            <h6 class="fw-bold text-secondary mb-3">Status Sidang</h6>
                            <span class="badge bg-success px-4 py-3 rounded-pill d-inline-flex align-items-center">
                                <i class="bi bi-check-circle me-6"></i> Lulus
                            </span>
                        </div>


                        <div class="text-center mt-4">
                            <h1 class="display-4 fw-bold text-success">95</h1>
                            <p class="text-muted">Nilai Akhir Sidang Sempro</p>
                        </div>
                    </div>

                    <!-- Sidang Akhir -->
                    <div class="tab-pane fade" id="akhir" role="tabpanel">
                        <h5 class="fw-bold mb-3 text-secondary">Judul Sidang</h5>
                        <p class="fs-5 text-muted mb-4">Sistem Informasi Akademik</p>

                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Tanggal Sidang</div>
                            <div class="col-sm-7">15 Mei 2025</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Dosen Pembimbing</div>
                            <div class="col-sm-7">Dr. Budi Santoso, M.Kom</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 fw-semibold">Dosen Penguji</div>
                            <div class="col-sm-7">Prof. Sari Dewi, M.T., Dr. Agus Salim, M.Kom,
                                 Dr. Wawan Setiawan, M.Kom,  Dr. Lina Marlina, M.Kom </div>
                        </div>

                        <div class="mt-4">
                        <h6 class="fw-bold text-secondary mb-2">Rincian Nilai</h6>
                        <ul class="list-unstyled ms-3 fs-6">
                            <li><i class="bi bi-file-earmark-text me-2 text-primary"></i> Kelengkapan Dokumen: <strong>24</strong></li>
                            <li><i class="bi bi-journal-text me-2 text-primary"></i> Pemahaman Materi: <strong>28</strong></li>
                            <li><i class="bi bi-easel me-2 text-primary"></i> Penyajian Sidang: <strong>26</strong></li>
                            <li><i class="bi bi-chat-left-dots me-2 text-primary"></i> Tanya Jawab: <strong>25</strong></li>
                        </ul>
                    </div>  

                    <!-- Nilai Per Dosen Penguji -->
                    <div class="mt-4">
                        <h6 class="fw-bold text-secondary mb-2">Nilai dari Masing-masing Dosen Penguji</h6>
                        <ul class="list-unstyled ms-3 fs-6">
                            <li><i class="bi bi-person-square me-2 text-primary"></i> Prof. Sari Dewi, M.T.: <strong>91</strong></li>
                            <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Agus Salim, M.Kom: <strong>90</strong></li>
                            <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Wawan Setiawan, M.Kom: <strong>60</strong></li>
                            <li><i class="bi bi-person-square me-2 text-primary"></i> Dr. Lina Marlina, M.Kom: <strong>55</strong></li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold text-secondary mb-3">Status Sidang</h6>
                        <span class="badge bg-warning text-dark px-4 py-3 rounded-pill d-inline-flex align-items-center">
                            <i class="bi bi-exclamation-triangle me-6"></i> Lulus dengan Revisi
                        </span>
                    </div>

                    <div class="text-center mt-4">
                        <h1 class="display-4 fw-bold text-success">74</h1>
                        <p class="text-muted">Nilai Akhir Sidang Tugas Akhir</p>
                    </div>
                    </div>
                    </div>
                </div> <!-- end tab-content -->
            </div>
        </div>
    </div>
</div>

