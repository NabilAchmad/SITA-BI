@extends('layouts.template.main')

@php
    $prioritasRole = ['kajur', 'kaprodi', 'dosen', 'tamu'];

    // Ambil semua nama_role dari user
    $userRoles = $role->pluck('nama_role')->toArray(); // misalnya ['dosen', 'kaprodi']

    // Ambil role dengan prioritas tertinggi
    $utama = collect($prioritasRole)->first(fn($r) => in_array($r, $userRoles)) ?? 'dosen';
@endphp

@section('title', 'Dashboard - ' . ucfirst($utama))

@section('content')
    <!-- Header Main -->
    @include('layouts.components.content-dosen.header')
    <!-- end header main -->

    <!-- Main Cards (Card 1 - 4) -->
    <div class="row">
        @include('layouts.components.content-dosen.card-1')
        @include('layouts.components.content-dosen.card-2')
        @include('layouts.components.content-dosen.card-3')
        @include('layouts.components.content-dosen.card-4')
    </div>
    <!-- end main cards -->

    <!-- JADWAL BIMBINGAN KHUSUS PEMBIMBING -->
    @if (in_array('pembimbing1', $peranDosen) || in_array('pembimbing2', $peranDosen))
        @include('layouts.components.content-dosen.jadwal-bimbingan')
    @endif

    <!-- JADWAL SIDANG KHUSUS PENGUJI -->
    @if (collect($peranDosen)->filter(fn($r) => str_contains($r, 'penguji'))->isNotEmpty())
        @include('layouts.components.content-dosen.jadwal-sidang')
    @endif

    <!-- Topik: wajib tampil untuk semua dosen -->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.components.content-dosen.topik')
        </div>
    </div>

    <!-- Pengumuman: wajib tampil -->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.components.content-dosen.pengumuman')
        </div>
    </div>

    <!-- RIWAYAT PENGAJUAN TA HANYA UNTUK KAJUR & KAPRODI -->
    @if (in_array('kajur', $userRoles) || in_array('kaprodi', $userRoles))
        <div class="row">
            @include('layouts.components.content-dosen.riwayatpengajuanta')
        </div>
    @endif
@endsection
