@extends('layouts.template.main')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-dosen.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- dosen section -->
        @include('layouts.components.content-dosen.dosen')
        <!-- End dosen section -->

        <!-- Mahasiswa section -->
        @include('layouts.components.content-dosen.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- dosen Penguji section -->
        @include('layouts.components.content-dosen.dospeng')
        <!-- End Sales dosen Penguji -->

        <!-- dosen Pembimbing section -->
        @include('layouts.components.content-dosen.dospem')
        <!-- End dosen Pembimbing -->
    </div>
    <!-- end main -->

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.components.content-dosen.logactivity')
        <!-- end Log Activity -->

        <!-- dosen aktif -->
        <div class="col-md-4">
            @include('layouts.components.content-dosen.dosenaktif')
        </div>
        <!-- end dosen aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            @include('layouts.components.content-dosen.pengumuman')
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.components.content-dosen.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection

