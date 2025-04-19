@extends('layouts.template.admin')
@section('title', 'Lihat Jadwal Sidang')

@section('content')
    @include('admin.sidang.jadwal.crud-jadwal.read')
@endsection
