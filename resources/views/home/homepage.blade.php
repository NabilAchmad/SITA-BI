{{-- resources/views/homepage.blade.php --}}
@extends('layouts.template.homepage')

@section('title', 'Beranda')

@section('content')
    @include('layouts.components.content-homepage.hero')
    @include('layouts.components.content-homepage.tawarantopik')
    @include('layouts.components.content-homepage.jadwal')
    @include('layouts.components.content-homepage.pengumuman')
    @include('layouts.components.content-homepage.team')
@endsection
