@extends('layouts.template.main')

@section('title', 'Lihat Judul TA')

@section('content')
    @include('kaprodi.judulTA.crud-JudulTA.read')
@endsection
