<!-- Section Title -->
<div class="container section-title text-center mb-5" data-aos="fade-up">
    <h1 class="display-4 fw-bold text-primary">Pengumuman Terkini</h1>
    <p class="lead">Informasi penting untuk civitas akademika Program Studi</p>
</div>

<div class="container">
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Judul Pengumuman</th>
                            <th class="py-3">Isi Pengumuman</th>
                            <th class="py-3">Tanggal Publish</th>
                        </tr>
                    </thead>
                    <tbody id="pengumumanTable">
                        @if(isset($pengumuman) && count($pengumuman) > 0)
                            @foreach($pengumuman as $p)
                                <tr class="align-middle">
                                    <td class="fw-bold text-primary">{{ $p->judul }}</td>
                                    <td>{{ $p->isi }}</td>
                                    <td><span
                                            class="badge bg-info">{{ \Carbon\Carbon::parse($p->created_at)->format('d F Y') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">Tidak ada pengumuman</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>