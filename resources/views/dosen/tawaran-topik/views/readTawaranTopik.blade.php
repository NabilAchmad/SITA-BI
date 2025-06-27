@extends('layouts.template.main')

@section('title', 'Lihat tawaran topik')

@section('content')
    @include('dosen.tawaran-topik.crud-TawaranTopik.read')
@endsection