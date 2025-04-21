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

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.components.content-kajur.logactivity')
        <!-- end Log Activity -->

        <!-- dosen aktif -->
        <div class="col-md-4">
            @include('layouts.components.content-kajur.dosenaktif')
        </div>
        <!-- end dosen aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            <div class="card card-round">
                @include('layouts.components.content-kajur.pengumuman')
            </div>
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.components.content-kajur.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection
