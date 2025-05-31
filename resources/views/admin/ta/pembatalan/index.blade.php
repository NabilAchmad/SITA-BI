@extends('layouts.template.main')

@section('title', 'Riwayat Pembatalan Tugas Akhir')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Riwayat Pembatalan Tugas Akhir</h3>

    @if ($riwayatPembatalan->isEmpty())
        <div class="alert alert-info">Tidak ada riwayat pembatalan Tugas Akhir.</div>
    @else
        <ul class="list-group">
            @foreach ($riwayatPembatalan as $item)
                <li class="list-group-item">
                    <strong>{{ $item->alasan }}</strong><br>
                    <small class="text-muted">Tanggal: {{ $item->created_at->format('d M Y') }}</small>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
