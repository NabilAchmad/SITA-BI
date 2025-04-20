@extends('layouts.template.main')

@section('title', 'Lihat Pengumuman')

@section('content')
    @include('admin.pengumuman.crud-pengumuman.read')
@endsection
