@extends('layouts.template.main')

@section('title', 'Pengumuman Dihapus')
@section('content')
    @include('admin.pengumuman.crud-pengumuman.trashed')
@endsection