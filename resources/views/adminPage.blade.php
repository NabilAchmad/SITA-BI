@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    @include('layouts.admin.partials.visitor')
    @include('layouts.admin.partials.subscriber')
    @include('layouts.admin.partials.sales')
    @include('layouts.admin.partials.order')
    @include('layouts.admin.main.upperMain.userstatistics')
    @include('layouts.admin.main.upperMain.dailysales')
    @include('layouts.admin.main.lowerMain.headercard')
    @include('layouts.admin.main.lowerMain.grafik')
    @include('layouts.admin.main.lowerMain.customer')
    @include('layouts.admin.main.lowerMain.transactionhistory')
@endsection