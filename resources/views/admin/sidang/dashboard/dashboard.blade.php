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
        <div class="row g-4">
            @php
                $cards = [
                    [
                        'title' => 'Menunggu Sidang Sempro',
                        'icon' => 'bi-hourglass-split',
                        'color' => 'primary',
                        'count' => $waitingSemproCount ?? 0,
                        'route' => route('sidang.menunggu.penjadwalan.sempro'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Mahasiswa yang belum dijadwalkan',
                    ],
                    [
                        'title' => 'Menunggu Sidang Akhir',
                        'icon' => 'bi-hourglass-top',
                        'color' => 'success',
                        'count' => $waitingAkhirCount ?? 0,
                        'route' => route('sidang.menunggu.penjadwalan.akhir'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Mahasiswa menunggu sidang akhir',
                    ],
                    [
                        'title' => 'Jadwal Sidang Sempro',
                        'icon' => 'bi-calendar-event',
                        'color' => 'warning',
                        'count' => $scheduledSemproCount ?? 0,
                        'route' => route('jadwal.sidang.sempro'),
                        'btn' => 'Lihat Jadwal',
                        'desc' => 'Total jadwal sempro aktif',
                    ],
                    [
                        'title' => 'Jadwal Sidang Akhir',
                        'icon' => 'bi-calendar-check',
                        'color' => 'info',
                        'count' => $scheduledAkhirCount ?? 0,
                        'route' => route('jadwal.sidang.akhir'),
                        'btn' => 'Lihat Jadwal',
                        'desc' => 'Total jadwal sidang akhir aktif',
                    ],
                    [
                        'title' => 'Pasca Sidang Sempro',
                        'icon' => 'bi-clipboard-check',
                        'color' => 'secondary',
                        'count' => $pascaSemproCount ?? 0,
                        'route' => route('pasca.sidang.sempro'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Data hasil sidang proposal',
                    ],
                    [
                        'title' => 'Pasca Sidang Akhir',
                        'icon' => 'bi-journal-check',
                        'color' => 'dark',
                        'count' => $pascaAkhirCount ?? 0,
                        'route' => route('pasca.sidang.akhir'),
                        'btn' => 'Lihat Detail',
                        'desc' => 'Rekap nilai sidang akhir',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100 dashboard-card">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-{{ $card['color'] }} text-white me-3 shadow-sm">
                                    <i class="bi {{ $card['icon'] }} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-{{ $card['color'] }} fw-semibold">{{ $card['title'] }}</h6>
                                    <small class="text-muted">{{ $card['desc'] }}</small>
                                </div>
                            </div>

                            <div class="flex-grow-1 d-flex align-items-center justify-content-center my-3">
                                <h2 class="fw-bold mb-0 text-{{ $card['color'] }}">
                                    <span class="count-up" data-count="{{ $card['count'] }}">{{ $card['count'] }}</span>
                                </h2>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ $card['route'] }}"
                                    class="btn btn-sm btn-outline-{{ $card['color'] }} w-100 py-2">
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
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .count-up {
            font-size: 2.5rem;
            letter-spacing: 1px;
            font-variant-numeric: tabular-nums;
        }

        @media (max-width: 768px) {
            .count-up {
                font-size: 2rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.count-up').forEach(function(el) {
                const target = +el.getAttribute('data-count');
                let count = 0;
                if (target === 0) return;
                const increment = Math.ceil(target / 40);
                const update = () => {
                    count += increment;
                    if (count > target) count = target;
                    el.textContent = count;
                    if (count < target) {
                        requestAnimationFrame(update);
                    }
                };
                update();
            });
        });
    </script>
@endpush
