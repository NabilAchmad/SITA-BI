@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Halaman -->
    @include('layouts.admin.header')
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

    <!-- upper main -->
    <div class="row">
        <!-- Log Activity -->
        @include('layouts.admin.main.upperMain.logactivity')
        <!-- end Log Activity -->

        <!-- Daily Sales -->
        <div class="col-md-4">
            @include('layouts.admin.main.upperMain.proposal')
            @include('layouts.admin.main.upperMain.dosenaktif')
        </div>
        <!-- end Daily Sales -->
    </div>

    <!-- Lower Main -->
    <div class="row">
        <div class="col-md-12">
            <!-- Grafik -->
            <div class="card card-round">
                <!-- user geolocation -->
                @include('layouts.admin.main.lowerMain.headercard')
                <!-- end user geolocation -->

                <!--Table-->
                @include('layouts.admin.main.lowerMain.grafik')
            </div>
            <!-- End Grafik -->
        </div>
    </div>

    <div class="row">
        <!-- New Customer -->
        @include('layouts.admin.main.lowerMain.customer')
        <!-- End New Customer -->

        <!-- Transaction History -->
        @include('layouts.admin.main.lowerMain.transactionhistory')
        <!-- End Transaction History -->
    </div>
@endsection
