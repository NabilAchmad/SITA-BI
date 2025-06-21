@extends('layouts.template.main')

@section('title', 'mahasiswa')

@section('content')
@include('admin.mahasiswa.crud-dospem.mhs-tanpa-pembimbing')
@endsection
