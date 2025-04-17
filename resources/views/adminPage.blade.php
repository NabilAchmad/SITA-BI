@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Halaman -->
    @include('layouts.admin.header')
    <div class="row">
        <!-- dosen section -->
        @include('layouts.admin.partials.dosen')
        <!-- End dosen section -->

        <!-- subscriber section -->
        @include('layouts.admin.partials.subscriber')
        <!-- End subscriber section -->

        <!-- Sales section -->
        @include('layouts.admin.partials.sales')
        <!-- End Sales section -->

        <!-- Order section -->
        @include('layouts.admin.partials.order')
        <!-- End Order section -->
    </div>

    <!-- upper main -->
    <div class="row">
        <!-- user statistics -->
        @include('layouts.admin.main.upperMain.userstatistics')
        <!-- end user statistics -->

        <!-- Daily Sales -->
        @include('layouts.admin.main.upperMain.dailysales')
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
