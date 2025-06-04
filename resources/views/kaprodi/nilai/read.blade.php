@extends('layouts.template.kaprodi')

@section('title', 'Nilai Mahasiswa')
@section('content')
    @include('kaprodi.nilai.crud-nilai.read')
@endsection
