@extends('layouts.template.main')

@section('title', 'List Mahasiswa Sidang')

@section('content')
@include('admin.sidang.mahasiswa.crud.tabel-mahasiswa-sidang')
@endsection