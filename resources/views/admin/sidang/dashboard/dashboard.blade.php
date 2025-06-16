@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@section('content')
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    <i class="bi bi-columns-gap me-2 text-primary"></i> Dashboard Sidang
                </h1>
                <p class="text-muted mb-0">Statistik pelaksanaan sidang Tugas Akhir dan perkembangannya</p>
            </div>
        </div>

        {{-- STATISTIC CARDS --}}
        <div class="row justify-content-center g-4">
            @php
                $cards = [
                    [
                        'title' => 'Kelola Jadwal Seminar Proposal',
                        'icon' => 'bi-hourglass-split',
                        'color' => 'primary',
                        'route' => route('sidang.kelola.sempro'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Mahasiswa yang belum dijadwalkan',
                    ],
                    [
                        'title' => 'Kelola Jadwal Sidang Akhir',
                        'icon' => 'bi-hourglass-top',
                        'color' => 'success',
                        'route' => route('sidang.kelola.akhir'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Mahasiswa menunggu sidang akhir',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card shadow-sm border-0 rounded-4 h-100 dashboard-card">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-{{ $card['color'] }} text-white me-3 shadow-sm">
                                    <i class="bi {{ $card['icon'] }} fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-{{ $card['color'] }} fw-semibold">{{ $card['title'] }}</h5>
                                    <small class="text-muted">{{ $card['desc'] }}</small>
                                </div>
                            </div>

                            <div class="mt-auto pt-3">
                                <a href="{{ $card['route'] }}" class="btn btn-outline-{{ $card['color'] }} w-100 py-2">
                                    <i class="bi bi-arrow-right me-1"></i> {{ $card['btn'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.07);
        }

        .icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
@endpush
