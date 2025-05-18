@extends('layouts.template.main')

@section('title', 'Buat Berita Acara')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4 fw-bold">Buat Berita Acara</h1>

    {{-- Tab Button --}}
    <div class="d-flex justify-content-center mb-4">
        <button id="btn-pra" onclick="toggleForm('pra')" class="btn btn-outline-primary me-2">Pra Sidang</button>
        <button id="btn-pasca" onclick="toggleForm('pasca')" class="btn btn-outline-success">Pasca Sidang</button>
    </div>

    {{-- Container with card layout --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- Pra Sidang --}}
            <div id="form-pra" class="card shadow-sm mb-4" style="display: none;">
                <div class="card-body">
                    @include('admin.berita-acara.crud-berita-acara.create-pra-sidang')
                </div>
            </div>

            {{-- Pasca Sidang --}}
            <div id="form-pasca" class="card shadow-sm mb-4" style="display: none;">
                <div class="card-body">
                    @include('admin.berita-acara.crud-berita-acara.create-pasca-sidang')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS untuk toggle dan efek tombol aktif --}}
<script>
    function toggleForm(type) {
        // Hide all forms
        document.getElementById('form-pra').style.display = 'none';
        document.getElementById('form-pasca').style.display = 'none';

        // Reset button classes
        document.getElementById('btn-pra').classList.remove('active');
        document.getElementById('btn-pasca').classList.remove('active');

        // Show selected form and activate button
        if (type === 'pra') {
            document.getElementById('form-pra').style.display = 'block';
            document.getElementById('btn-pra').classList.add('active');
        } else if (type === 'pasca') {
            document.getElementById('form-pasca').style.display = 'block';
            document.getElementById('btn-pasca').classList.add('active');
        }
    }
</script>
@endsection
