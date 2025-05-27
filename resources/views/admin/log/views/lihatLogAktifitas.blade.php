@extends('layouts.template.main')

@section('title', 'Log dan Aktivitas')

@section('content')
    @include('admin.log.crud-log.read')
@endsection
