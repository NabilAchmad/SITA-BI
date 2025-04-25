@extends('layouts.template.dospeng')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-dospeng.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- dosen section -->
        @include('layouts.components.content-dospeng.dosen')
        <!-- End dosen section -->

        <!-- Mahasiswa section -->
        @include('layouts.components.content-dospeng.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- Dosen Penguji section -->
        @include('layouts.components.content-dospeng.dospeng')
        <!-- End Sales Dosen Penguji -->

        <!-- Dosen Pembimbing section -->
        @include('layouts.components.content-dospeng.dospeng')
        <!-- End Dosen Pembimbing -->
    </div>
    <!-- end main -->

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.components.content-dospeng.logactivity')
        <!-- end Log Activity -->

        <!-- dosen aktif -->
        <div class="col-md-4">
            @include('layouts.components.content-dospeng.dosenaktif')
        </div>
        <!-- end dosen aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            <div class="card card-round">
                @include('layouts.components.content-dospeng.pengumuman')
            </div>
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.components.content-dospeng.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection