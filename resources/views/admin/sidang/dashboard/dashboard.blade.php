@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@section('content')
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1"><i class="bi bi-columns-gap me-2 text-primary"></i> Dashboard Sidang</h1>
                <p class="text-muted mb-0">Statistik pelaksanaan sidang Tugas Akhir dan perkembangannya.</p>
            </div>
        </div>

        <div class="row g-4">
            @php
                $cards = [
                   
                    [
                        'title' => 'Jadwal Sidang Sempro',
                        'icon' => 'bi-calendar-event',
                        'color' => 'warning',
                        'count' => $scheduledSemproCount ?? 0,
                        'route' => route('jadwal.sidang.sempro'),
                        'btn' => 'Lihat Jadwal',
                    ],
                    [
                        'title' => 'Jadwal Sidang Akhir',
                        'icon' => 'bi-calendar-check',
                        'color' => 'info',
                        'count' => $scheduledAkhirCount ?? 0,
                        'route' => route('jadwal.sidang.akhir'),
                        'btn' => 'Lihat Jadwal',
                    ],

                    [
                        'title' => 'Penilaian Sidang Akhir',
                        'icon' => 'bi-pencil-square',
                        'color' => 'danger',
                        'count' => $nilaiSidangCount ?? 0,
                        'route' => route('penilaian.sidang.index'), 
                        'btn' => 'Beri Nilai',
                    ],
                ];
            @endphp

            {{-- STATISTIC CARDS --}}
            <div class="row g-4 mb-4">
                @foreach ($cards as $card)
                    <div class="col-md-6 col-xl-4">
                        <div class="card shadow-sm border-0 rounded-3 h-100 dashboard-card">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-{{ $card['color'] }} text-white me-3 shadow-sm">
                                        <i class="bi {{ $card['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-{{ $card['color'] }} fw-semibold">{{ $card['title'] }}</h6>
                                        <small class="text-muted">
                                            @switch($card['title'])

                                                @case('Jadwal Sidang Sempro')
                                                    Total jadwal sempro aktif.
                                                @break

                                                @case('Jadwal Sidang Akhir')
                                                    Total jadwal sidang akhir aktif.
                                                @break

                                                @case('Penilaian Sidang Akhir')
                                                    Data hasil penilaian sidang.
                                                @break
                                            @endswitch
                                        </small>
                                    </div>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                    <h2 class="fw-bold mb-0 text-{{ $card['color'] }}">
                                        <span class="count-up" data-count="{{ $card['count'] }}">{{ $card['count'] }}</span>
                                    </h2>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $card['route'] }}"
                                        class="btn btn-sm btn-outline-{{ $card['color'] }} w-100">
                                        {{ $card['btn'] }}
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
