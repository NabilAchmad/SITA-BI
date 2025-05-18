@extends('layouts.template.main')

@section('title', 'Tambah Akun Dosen')

@section('content')

    @include('layouts.dosen.addDosen')

    @include('admin.kelola-akun.dosen.crud-dosen.create')

@endsection