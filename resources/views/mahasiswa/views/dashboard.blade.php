@extends('layouts.template.mahasiswa')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-mahasiswa.header')
    <!-- end header main -->

    <!-- main -->
    <div class="row">
    </div>
    <!-- end main -->

    <!-- main -->
    <div class="row">

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
