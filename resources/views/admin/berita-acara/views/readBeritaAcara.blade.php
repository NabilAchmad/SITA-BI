@extends('layouts.template.main')
@section('title', 'Lihat Berita Acara')

@section('content')
    @include('admin.berita-acara.crud-berita-acara.read')
@endsection
