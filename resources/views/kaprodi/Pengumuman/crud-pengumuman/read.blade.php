<!-- Section Title -->
<div class="container section-title text-center mb-5" data-aos="fade-up">
    <h1 class="display-4 fw-bold text-primary">Pengumuman Terkini</h1>
    <p class="lead text-muted"><i class="fas fa-bullhorn me-2"></i>Informasi penting untuk civitas akademika Program Studi</p>
</div>

<div class="container">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary bg-gradient p-4">
            <h4 class="text-white mb-0"><i class="fas fa-clipboard-list me-2"></i>Daftar Pengumuman</h4>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle table-striped">
                    <thead class="bg-primary bg-opacity-75 text-white">
                        <tr>
                            <th class="py-3 px-4"><i class="fas fa-heading me-2"></i>Judul Pengumuman</th>
                            <th class="py-3 px-4"><i class="fas fa-file-alt me-2"></i>Isi Pengumuman</th>
                            <th class="py-3 px-4"><i class="fas fa-calendar-alt me-2"></i>Tanggal Publish</th>
                        </tr>
                    </thead>
                    <tbody id="pengumumanTable">
                        @if(isset($pengumuman) && count($pengumuman) > 0)
                            @foreach($pengumuman as $p)
                                <tr class="align-middle hover-shadow transition-all">
                                    <td class="fw-bold text-primary py-4">{{ $p->judul }}</td>
                                    <td class="py-4">{{ $p->isi }}</td>
                                    <td class="py-4">
                                        <span class="badge bg-info rounded-pill px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($p->created_at)->format('d F Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="py-5 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">Tidak ada pengumuman saat ini</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>