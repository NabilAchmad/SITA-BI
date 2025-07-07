<div class="table-responsive rounded-bottom shadow-sm">
    <table class="table align-middle text-center table-hover mb-0" style="font-size: 0.96rem;">
        <thead class="bg-light text-secondary fw-semibold border-bottom">
            <tr style="border-bottom: 1px solid #dee2e6;">
                <th style="width: 5%;">No</th>
                <th class="text-start">Nama Mahasiswa</th>
                <th style="width: 15%;">NIM</th>
                <th style="width: 15%;">Program Studi</th>
                <th class="text-start">Judul Tugas Akhir</th>
                <th style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tugasAkhirCollection as $ta)
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-start">
                        {{ $ta->mahasiswa?->user?->name ?? 'Mahasiswa tidak ditemukan' }}
                    </td>
                    <td>{{ $ta->mahasiswa?->nim ?? '-' }}</td>
                    <td>
                        @if ($ta->mahasiswa)
                            <span
                                class="badge 
                                {{ strtolower($ta->mahasiswa->prodi) === 'd3' ? 'bg-info-subtle text-dark' : 'bg-primary-subtle text-dark' }} 
                                fw-medium rounded-pill px-3 py-1 shadow-sm">
                                {{ strtolower($ta->mahasiswa->prodi) === 'd3' ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris' }}
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-dark rounded-pill px-3 py-1">N/A</span>
                        @endif
                    </td>
                    <td class="text-start">{{ $ta->judul }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                            data-id="{{ $ta->id }}">
                            <i class="bi bi-search me-1"></i> Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                        Tidak ada data pengajuan pada kategori ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        table tbody tr:hover {
            background-color: #f9f9f9;
            transition: background-color 1s ease-in-out;
        }
    </style>

    <style>
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0.3;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <style>
        .nav-tabs .nav-link.active {
            background-color: #e9f5ff;
            color: #0d6efd !important;
            border-radius: 0.5rem 0.5rem 0 0;
        }
    </style>
@endpush
