@extends('layouts.template.main')

@section('title', 'Kelola Akun Mahasiswa')

@section('content')
    @include('admin.kelola-akun.mahasiswa.crud-mahasiswa.read')
@endsection
