@extends('layouts.template.main')

@section('title', 'Kelola Akun Dosen')
@section('content')
    @include('admin.kelola-akun.dosen.crud-dosen.read')
@endsection
