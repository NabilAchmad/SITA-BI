@extends('layouts.template.dospem')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-dospem.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
        <!-- dosen section -->
        @include('layouts.components.content-dospem.dosen')
        <!-- End dosen section -->

        <!-- Mahasiswa section -->
        @include('layouts.components.content-dospem.mahasiswa')
        <!-- End Mahasiswa section -->

        <!-- Dosen Penguji section -->
        @include('layouts.components.content-dospem.dospeng')
        <!-- End Sales Dosen Penguji -->

        <!-- Dosen Pembimbing section -->
        @include('layouts.components.content-dospem.dospem')
        <!-- End Dosen Pembimbing -->
    </div>
    <!-- end main -->

    <!-- main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.components.content-dospem.logactivity')
        <!-- end Log Activity -->

        <!-- dosen aktif -->
        <div class="col-md-4">
            @include('layouts.components.content-dospem.dosenaktif')
        </div>
        <!-- end dosen aktif -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Pengumuman -->
            <div class="card card-round">
                @include('layouts.components.content-dospem.pengumuman')
            </div>
            <!-- End Pengumuman -->
        </div>
    </div>

    <div class="row">
        <!-- Transaction History -->
        @include('layouts.components.content-dospem.riwayatpengajuanta')
        <!-- End Transaction History -->
    </div>
    <!-- end main -->
@endsection