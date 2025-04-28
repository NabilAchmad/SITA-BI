@extends('layouts.template.main')

@section('title', 'List Mahasiswa Sidang')

@section('content')
@include('admin.sidang.jadwal.crud-jadwal.tabel-mahasiswa-sidang')
@endsection