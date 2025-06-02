<div class="container section-title" data-aos="fade-up">
    <h1>Judul Tugas Akhir - ACC dan Ditolak</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Judul ACC</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center" id="accTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal ACC</th>
                            <th>Judul Tugas Akhir</th>
                            <th>Pengaju</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvedJuduls as $accJudul)
                            <tr id="acc-row-{{ $accJudul->id }}">
                                <td>{{ $accJudul->tanggal_acc ? $accJudul->tanggal_acc->format('d-m-Y H:i') : '-' }}</td>
                                <td>{{ $accJudul->judul }}</td>
                                <td>{{ $accJudul->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Judul Ditolak</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center" id="rejectedTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul Tugas Akhir</th>
                            <th>Pengaju</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rejectedJuduls as $rejectedJudul)
                            <tr id="rejected-row-{{ $rejectedJudul->id }}">
                                <td>{{ $rejectedJudul->judul }}</td>
                                <td>{{ $rejectedJudul->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
