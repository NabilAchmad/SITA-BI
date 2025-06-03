<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">

    <h1 class="modern-title"><i class="bi bi-check-circle-fill text-success me-2 pulse-icon"></i>ACC Nilai Tugas Akhir</h1>
</div>

<div class="container">
    <ul class="nav nav-tabs nav-tabs-modern" id="nilaiTabs" role="tablist">
        <li class="nav-item" role="presentation">

            <button class="nav-link active hover-effect" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                role="tab" aria-controls="semua" aria-selected="true">


                <i class="bi bi-list-ul me-2 icon-bounce"></i>Semua Nilai
                <span class="badge bg-primary ms-2 badge-pulse">{{ count($nilais) }}</span>
            </button>
        </li>
        {{-- <li class="nav-item" role="presentation">

            <button class="nav-link hover-effect" id="acc-tab" data-bs-toggle="tab" data-bs-target="#acc" type="button" role="tab"
                aria-controls="acc" aria-selected="false">

                <i class="bi bi-check-circle-fill me-2 text-success icon-bounce"></i>Nilai Disetujui
            </button>
        </li>
        <li class="nav-item" role="presentation">

            <button class="nav-link hover-effect" id="tolak-tab" data-bs-toggle="tab" data-bs-target="#tolak" type="button"
                role="tab" aria-controls="tolak" aria-selected="false">

                <i class="bi bi-x-circle-fill me-2 text-danger icon-bounce"></i>Nilai Ditolak
            </button>
        </li> --}}
    </ul>

    <div class="tab-content pt-4">
        <!-- Tab Semua -->
        <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle custom-table animate-table">
                    <thead class="table-dark">
                        <tr>




                            <th scope="col"><i class="bi bi-person-badge me-2 icon-bounce"></i>Nama Mahasiswa</th>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2 icon-bounce"></i>Nilai Tugas Akhir</th>
                            <th scope="col"><i class="bi bi-info-circle me-2 icon-bounce"></i>Status</th>
                            {{-- <th scope="col"><i class="bi bi-gear me-2 icon-bounce"></i>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody id="nilaiTable">
                        @foreach ($nilais as $nilai)

                            <tr id="row-{{ $nilai->id }}" class="align-middle hover-row">
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">


                                        <i class="bi bi-person-circle me-2 fs-4 profile-icon"></i>
                                        <span class="fw-bold">{{ $nilai->mahasiswa->nama ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="text-start fw-bold">{{ $nilai->nilai }}</td>
                                <td id="status-{{ $nilai->id }}">
                                    @if ($nilai->status == 'Disetujui')

                                        <span class="badge bg-success status-badge"><i
                                                class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                    @elseif ($nilai->status == 'Ditolak')

                                        <span class="badge bg-danger status-badge"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @else

                                        <span class="badge bg-warning status-badge"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">


                                        <span class="text-muted fst-italic">Hanya untuk melihat</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab ACC -->
        <div class="tab-pane fade" id="acc" role="tabpanel" aria-labelledby="acc-tab">
            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle custom-table animate-table">
                    <thead class="table-success">
                        <tr>



                            <th scope="col"><i class="bi bi-calendar-check-fill me-2 icon-bounce"></i>Tanggal ACC</th>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2 icon-bounce"></i>Nilai</th>
                            <th scope="col"><i class="bi bi-person-badge me-2 icon-bounce"></i>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="accTable">
                        @foreach ($nilais->where('status', 'Disetujui') as $nilai)

                            <tr class="align-middle hover-row">
                                <td>{{ \Carbon\Carbon::parse($nilai->tanggal_acc)->format('d-m-Y') ?? '-' }}</td>


                                <td class="text-start fw-bold">{{ $nilai->nilai }}</td>
                                <td class="fw-bold">{{ $nilai->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Ditolak -->
        <div class="tab-pane fade" id="tolak" role="tabpanel" aria-labelledby="tolak-tab">
            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle custom-table animate-table">
                    <thead class="table-danger">
                        <tr>


                            <th scope="col"><i class="bi bi-file-earmark-text me-2 icon-bounce"></i>Nilai</th>
                            <th scope="col"><i class="bi bi-person-badge me-2 icon-bounce"></i>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="tolakTable">
                        @foreach ($nilais->where('status', 'Ditolak') as $nilai)



                            <tr class="align-middle hover-row">
                                <td class="text-start fw-bold">{{ $nilai->nilai }}</td>
                                <td class="fw-bold">{{ $nilai->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .modern-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 2rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .nav-tabs-modern {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 2rem;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        color: #6c757d;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        font-weight: 500;
    }

    .nav-tabs-modern .nav-link:hover {
        color: #0d6efd;

        transform: translateY(-2px);
    }

    .nav-tabs-modern .nav-link.active {
        color: #0d6efd;
        border: none;

        border-bottom: 3px solid #0d6efd;
        margin-bottom: -2px;
    }

    .custom-table {


        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }

    .custom-table thead th {
        font-weight: 600;
        text-transform: uppercase;

        letter-spacing: 1px;
        padding: 1.2rem;
        background: linear-gradient(45deg, #1a237e, #283593);
        color: white;
    }





    .table-success thead th {
        background: linear-gradient(45deg, #2e7d32, #388e3c);
    }



    .table-danger thead th {
        background: linear-gradient(45deg, #c62828, #d32f2f);
    }

    .hover-row:hover {
        transform: scale(1.01);
        transition: transform 0.3s ease;
        background-color: rgba(13, 110, 253, 0.05);
    }

    .badge {

        padding: 0.6rem 1.2rem;
        font-weight: 500;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .status-badge {
        min-width: 120px;
    }

    .icon-bounce {
        animation: bounce 1s infinite;
    }

    .profile-icon {
        color: #0d6efd;
        transition: transform 0.3s ease;
    }

    .profile-icon:hover {
        transform: scale(1.2);
    }

    .animate-table {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .badge-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .hover-effect {
        transition: all 0.3s ease;
    }

    .hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
