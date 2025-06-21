@extends('layouts.template.main')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-mahasiswa.welcome')
    <!-- end header main -->

    <!-- card alur tugas akhir -->
    <div class="row g-4">
        @include('layouts.components.content-mahasiswa.progress')
    </div>

    <div class="row">
        <div class="col-md-12">

            <!-- Pengumuman -->
            <div class="card card-round">
                @include('layouts.components.content-mahasiswa.pengumuman')
            </div>
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">

    </div>
    <!-- end main -->
@endsection
