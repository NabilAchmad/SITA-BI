{{-- resources/views/layouts.homepage.blade.php --}}
@extends('layouts.homepage.app')

@section('title', 'Beranda')

@section('content')
    @include('layouts.homepage.partials.hero')
    @include('layouts.homepage.partials.tawarantopik')
    @include('layouts.homepage.partials.jadwal')
    @include('layouts.homepage.partials.pengumuman')
    @include('layouts.homepage.partials.nilai')
    @include('layouts.homepage.partials.team')
@endsection
