@extends('layouts.template.main')

@section('title', 'Tugas Akhir Mahasiswa')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden card-hover">
                    <div class="card-body p-6">
                        <div class="text-center mb-5">
                            <div class="position-relative d-inline-block mb-4">
                                <div class="bg-primary bg-opacity-10 p-4 rounded-circle">
                                    <i class="fas fa-file-alt text-white fa-3x"></i>
                                </div>
                                <div class="position-absolute top-0 start-100 translate-middle">
                                    <span class="badge bg-danger rounded-pill fs-7">New</span>
                                </div>
                            </div>

                            <h2 class="fw-bold mb-3 display-5 text-gradient-primary">Mulai Tugas Akhir Anda</h2>
                            <p class="lead text-muted mb-4">
                                Anda belum memiliki data Tugas Akhir yang aktif. Pilih salah satu opsi di bawah untuk
                                memulai perjalanan akademik Anda!
                            </p>
                        </div>

                        <div class="row g-4 justify-content-center">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="icon-xl bg-primary bg-opacity-10 text-primary rounded-circle mb-4">
                                            <i class="fas fa-file-upload text-white"></i>
                                        </div>
                                        <h4 class="fw-bold mb-3">Ajukan Mandiri</h4>
                                        <p class="text-muted mb-4">
                                            Ajukan judul tugas akhir Anda sendiri dengan bimbingan dosen
                                        </p>
                                        <a href="{{ route('mahasiswa.tugas-akhir.ajukan') }}"
                                            class="btn btn-primary rounded-pill px-4 stretched-link">
                                            Mulai Ajukan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="icon-xl bg-info bg-opacity-10 text-info rounded-circle mb-4">
                                            <i class="fas fa-lightbulb text-white"></i>
                                        </div>
                                        <h4 class="fw-bold mb-3">Ambil Topik</h4>
                                        <p class="text-muted mb-4">
                                            Pilih dari daftar topik yang ditawarkan oleh dosen
                                        </p>
                                        <a href="{{-- route('mahasiswa.topik.index') --}}"
                                            class="btn btn-outline-info rounded-pill px-4 stretched-link">
                                            Lihat Tawaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-3">
                            <p class="small text-muted mb-2">Butuh bantuan?</p>
                            <a href="#" class="btn btn-link text-decoration-none">
                                <i class="fas fa-question-circle me-2"></i> Panduan Tugas Akhir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .text-gradient-primary {
                background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .hover-lift {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, .125) !important;
            }

            .icon-xl {
                width: 64px;
                height: 64px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endpush
@endsection
