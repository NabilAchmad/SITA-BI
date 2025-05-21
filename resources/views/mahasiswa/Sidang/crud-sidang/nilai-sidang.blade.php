<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="card shadow-lg rounded-4 w-100" style="max-width: 1140px;">
        <div class="row g-0">
            <!-- Profil Mahasiswa -->
            <div class="col-md-4 bg-primary text-white d-flex flex-column align-items-center justify-content-center p-5 rounded-start-4">
                <img src="https://via.placeholder.com/160" class="rounded-circle mb-4" alt="Foto Mahasiswa" style="width: 140px; height: 140px; object-fit: cover;">
                <h3 class="mb-1">Kasih Ananda Nardi</h3>
                <span class="fs-5">NIM: 12345678</span>
            </div>

            <!-- Konten Nilai -->
            <div class="col-md-8 p-5">
                <ul class="nav nav-tabs justify-content-center mb-5 fs-5" id="nilaiSidangTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-4" id="sempro-tab" data-bs-toggle="tab" data-bs-target="#sempro" type="button" role="tab">
                            Sidang Sempro
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4" id="akhir-tab" data-bs-toggle="tab" data-bs-target="#akhir" type="button" role="tab">
                            Sidang Akhir
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="nilaiSidangTabContent">
                    <div class="tab-pane fade show active" id="sempro" role="tabpanel">
                        <h5 class="fw-semibold">Judul Sidang</h5>
                        <p class="text-muted fs-5">Sistem Informasi Akademik</p>
                        <h5 class="fw-semibold mt-4">Nilai Sidang Sempro</h5>
                        <p class="display-5 fw-bold text-success">95</p>
                    </div>
                    <div class="tab-pane fade" id="akhir" role="tabpanel">
                        <h5 class="fw-semibold">Judul Sidang</h5>
                        <p class="text-muted fs-5">Sistem Informasi Akademik</p>
                        <h5 class="fw-semibold mt-4">Nilai Sidang Akhir</h5>
                        <p class="display-5 fw-bold text-success">99</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
