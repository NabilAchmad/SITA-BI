@extends('layouts.template.main')

@section('title', 'Profile Admin')

@section('content')
    @include('admin.user.crud-users.view')
@endsection