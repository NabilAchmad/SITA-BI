@extends('layouts.template.main')

@section('title', 'Kelola Akun Mahasiswa')

@section('content')
    @include('admin.mahasiswa.crud-mahasiswa.read')
@endsection
