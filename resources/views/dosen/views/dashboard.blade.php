@extends('layouts.template.main')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-admin.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- admin section -->
        @include('layouts.components.content-admin.dosen')
        <!-- End admin section -->

        <!-- Mahasiswa section -->
        @include('layouts.components.content-admin.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- admin Penguji section -->
        @include('layouts.components.content-admin.dospeng')
        <!-- End Sales admin Penguji -->

        <!-- admin Pembimbing section -->
        @include('layouts.components.content-admin.dospem')
        <!-- End admin Pembimbing -->
    </div>
    <!-- end main -->

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.components.content-admin.logactivity')
        <!-- end Log Activity -->

        <!-- admin aktif -->
        <div class="col-md-4">
            @include('layouts.components.content-admin.dosenaktif')
        </div>
        <!-- end admin aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            @include('layouts.components.content-admin.pengumuman')
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.components.content-admin.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection

