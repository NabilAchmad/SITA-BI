@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@push('styles')
    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }

        .icon-container {
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .badge-detail {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .opacity-bg-icon {
            font-size: 7rem;
            position: absolute;
            bottom: -1.2rem;
            right: 1rem;
            opacity: 0.08;
        }

        .card-title-text {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-desc-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="position-relative overflow-hidden rounded-3 mb-4 p-4"
            style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
            <div class="position-relative z-1">
                <h4 class="fw-bold text-primary mb-1">
                    <i class="bi bi-columns-gap me-2"></i> Dashboard Sidang
                </h4>
                <p class="text-muted mb-0">Statistik pelaksanaan sidang Tugas Akhir dan perkembangannya</p>
            </div>
            <i class="bi bi-hourglass-split text-primary opacity-bg-icon"></i>
        </div>

        <!-- Cards -->
        <div class="row g-4">
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
                <div class="col-md-6 col-xl-6">
                    <a href="{{ $card['route'] }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-3 card-hover h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-container rounded-circle text-white bg-{{ $card['color'] }} me-3">
                                        <i class="bi {{ $card['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="card-title-text text-dark">{{ $card['title'] }}</div>
                                        <div class="card-desc-text">{{ $card['desc'] }}</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span
                                        class="badge badge-detail bg-soft-{{ $card['color'] }} text-{{ $card['color'] }} py-2 px-3">
                                        <i class="bi bi-arrow-right-circle me-1"></i> {{ $card['btn'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
