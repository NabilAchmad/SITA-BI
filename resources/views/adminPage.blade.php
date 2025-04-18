@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.admin.main.header')
    <div class="row">
        <!-- dosen section -->
        @include('layouts.admin.partials.dosen')
        <!-- End dosen section -->

        <!-- Mahasiswa section -->
        @include('layouts.admin.partials.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- Dosen Penguji section -->
        @include('layouts.admin.partials.dospeng')
        <!-- End Sales Dosen Penguji -->

        <!-- Dosen Pembimbing section -->
        @include('layouts.admin.partials.dospem')
        <!-- End Dosen Pembimbing -->
    </div>
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.admin.main.logactivity')
        <!-- end Log Activity -->

        <!-- dosen aktif -->
        <div class="col-md-4">
            @include('layouts.admin.main.dosenaktif')
        </div>
        <!-- end dosen aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            <div class="card card-round">
                @include('layouts.admin.main.pengumuman')
            </div>
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.admin.main.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection
