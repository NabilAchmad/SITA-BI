@extends('layouts.template.main')
@section('title', 'Lihat Jadwal Sidang')

@section('content')
    @include('admin.sidang.jadwal.crud-jadwal.tabel-jadwal-sidang')
@endsection
