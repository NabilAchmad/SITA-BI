@extends('layouts.template.main')

@section('title', 'Dashboard Tugas Akhir')

@section('content')
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1 text-primary"><i class="bi bi-mortarboard-fill me-2"></i> Dashboard Tugas Akhir</h1>
                <p class="text-muted mb-0">Kelola seluruh proses Tugas Akhir dari pengajuan hingga pemantauan progress.</p>
            </div>
        </div>

        @php
            $cards = [
                [
                    'title' => 'Kemajuan Tugas Akhir',
                    'desc' => 'Ajukan topik tugas akhir mandiri ke dosen pembimbing.',
                    'icon' => 'bi-lightbulb',
                    'color' => 'info',
                    'route' => route('ta.kemajuan.index'),
                ],
                [
                    'title' => 'Pembatalan Tugas Akhir',
                    'desc' => 'Ajukan topik yang ditawarkan dosen pembimbing.',
                    'icon' => 'bi-journal-text',
                    'color' => 'primary',
                    'route' => route('ta.pembatalan.index'),
                ],
                [
                    'title' => 'Revisi Tugas Akhir',
                    'desc' => 'Pantau bimbingan dan revisi tugas akhir Anda.',
                    'icon' => 'bi-list-check',
                    'color' => 'success',
                    'route' => route('ta.revisi.index'),
                ],
            ];
        @endphp

        <div class="row g-4">
            @foreach ($cards as $card)
                <div class="col-md-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-3 h-100 dashboard-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-{{ $card['color'] }} text-white me-3 shadow-sm">
                                    <i class="bi {{ $card['icon'] }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-{{ $card['color'] }}">{{ $card['title'] }}</h6>
                                    <small class="text-muted">{{ $card['desc'] }}</small>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ $card['route'] }}" class="stretched-link"></a>
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
            position: relative;
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
    </style>
@endpush
