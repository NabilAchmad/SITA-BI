<!-- Section Title -->
<div class="container section-title text-center mb-5" data-aos="fade-up">
    <h1 class="display-4 fw-bold text-primary">Pengumuman Terkini</h1>
    <p class="lead text-muted">Informasi penting untuk civitas akademika Program Studi</p>
</div>

<div class="container">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-white border-0 py-4">
            <i class="fas fa-bullhorn text-primary me-2"></i>
            <span class="fw-bold">Daftar Pengumuman</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle table-borderless">
                    <thead class="bg-primary text-white rounded-3">
                        <tr>
                            <th class="py-3 rounded-start">Judul Pengumuman</th>
                            <th class="py-3">Isi Pengumuman</th>
                            <th class="py-3 rounded-end">Tanggal Publish</th>
                        </tr>
                    </thead>
                    <tbody id="pengumumanTable">
                        @foreach($pengumumans as $pengumuman)
                        <tr class="align-middle hover-shadow">
                            <td class="fw-bold text-primary">{{ $pengumuman->judul }}</td>
                            <td class="text-muted">{{ $pengumuman->isi }}</td>
                            <td><span class="badge bg-primary rounded-pill px-3">{{ $pengumuman->tanggal_publish->format('d M Y') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
