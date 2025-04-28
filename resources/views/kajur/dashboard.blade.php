@extends('layouts.template.kajur')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-kajur.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- dosen section -->
        @include('layouts.components.content-kajur.dosen')
        <!-- End dosen section -->

        <!-- Mahasiswa section -->
        @include('layouts.components.content-kajur.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- Dosen Penguji section -->
        @include('layouts.components.content-kajur.dospeng')
        <!-- End Sales Dosen Penguji -->

        <!-- Dosen Pembimbing section -->
        @include('layouts.components.content-kajur.dospem')
        <!-- End Dosen Pembimbing -->
    </div>
    <!-- end main -->

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            @include('layouts.components.content-kajur.pengumuman')
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- pengajuan TA -->
        @include('layouts.components.content-kajur.riwayatpengajuanta')
        <!-- End pengajuan TA -->
    </div>
    <!-- end main -->
@endsection
