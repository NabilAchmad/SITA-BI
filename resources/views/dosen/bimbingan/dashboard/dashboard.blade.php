@extends('layouts.template.main')

@section('title', 'Dashboard Bimbingan')

@section('content')
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1"><i class="bi bi-journal-text me-2 text-primary"></i> Dashboard Bimbingan</h1>
                <p class="text-muted mb-0">Statistik aktivitas dan progres bimbingan Tugas Akhir.</p>
            </div>
        </div>

        <div class="row g-4">
            @php
                $cards = [
                    [
                        'title' => 'List jadwal Bimbingan',
                        'icon' => 'bi-hourglass',
                        'color' => 'secondary',
                        'count' => $notStartedCount ?? 0,
                        'route' => route('list-mhs-bimbingan'),
                        'btn' => 'Lihat Detail',
                    ],
                    [
                        'title' => 'Sedang Bimbingan',
                        'icon' => 'bi-person-lines-fill',
                        'color' => 'primary',
                        'count' => $ongoingCount ?? 0,
                        'route' => route('bimbingan.sedang.berlangsung'),
                        'btn' => 'Lihat Mahasiswa',
                    ],
                    [
                        'title' => 'Menunggu Review Dosen',
                        'icon' => 'bi-eye',
                        'color' => 'warning',
                        'count' => $waitingReviewCount ?? 0,
                        'route' => route('bimbingan.menunggu.review'),
                        'btn' => 'Lihat Detail',
                    ],
                    [
                        'title' => 'Selesai Bimbingan',
                        'icon' => 'bi-check2-circle',
                        'color' => 'success',
                        'count' => $completedCount ?? 0,
                        'route' => route('bimbingan.selesai'),
                        'btn' => 'Lihat Rekap',
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
                                                @case('Belum Memulai Bimbingan')
                                                    Mahasiswa belum melakukan sesi bimbingan.
                                                @break

                                                @case('Sedang Bimbingan')
                                                    Mahasiswa aktif dalam proses bimbingan.
                                                @break

                                                @case('Menunggu Review Dosen')
                                                    Menunggu tanggapan dosen pembimbing.
                                                @break

                                                @case('Selesai Bimbingan')
                                                    Bimbingan telah diselesaikan dan direkap.
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
