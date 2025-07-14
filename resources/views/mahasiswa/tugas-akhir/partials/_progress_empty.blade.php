@extends('layouts.template.main')

@section('title', 'Tugas Akhir Mahasiswa')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg card-hover">
                <div class="card-body text-center p-5">
                    <div
                        class="icon-box mx-auto mb-4 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-file-alt text-primary fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Belum Ada Data Tugas Akhir</h3>
                    <p class="text-muted mb-4">Anda belum memiliki data Tugas Akhir yang aktif. Mulai perjalanan
                        akademik Anda sekarang!</p>
                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                        <a href="{{ route('mahasiswa.tugas-akhir.ajukan') }}"
                            class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                            <i class="fas fa-file-upload me-2"></i> Ajukan Tugas Akhir Mandiri
                        </a>
                        <a href="{{-- route('mahasiswa.topik.index') --}}"
                            class="btn btn-outline-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                            <i class="fas fa-lightbulb me-2"></i> Ambil Tawaran Topik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
