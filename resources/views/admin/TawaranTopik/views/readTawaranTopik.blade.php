@extends('layouts.template.main')

@section('title', 'Lihat tawaran topik')

@section('content')
    @include('admin.TawaranTopik.crud-TawaranTopik.read')
@endsection
