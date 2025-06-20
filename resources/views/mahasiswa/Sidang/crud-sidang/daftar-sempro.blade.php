<h1>Form Pendaftaran Seminar Proposal</h1>

<form action="#" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nama Mahasiswa</label>
        <input type="text" class="form-control" value="Kasih Ananda Nardi" readonly>
    </div>

    <div class="mb-3">
        <label>NIM</label>
        <input type="text" class="form-control" value="2311081021" readonly>
    </div>

    <div class="mb-3">
        <label>Judul Proposal</label>
        <input type="text" name="judul_proposal" class="form-control" placeholder="Contoh: Sistem Informasi Seminar Proposal" required>
    </div>

    <div class="mb-3">
        <label>Dosen Pembimbing 1</label>
        <input type="text" class="form-control" value="Dr. Siti Nurhaliza, M.Kom" readonly>
    </div>

    <div class="mb-3">
        <label>Dosen Pembimbing 2</label>
        <input type="text" class="form-control" value="Prof. Bambang Pamungkas, M.T." readonly>
    </div>

    <div class="mb-3">
        <label>Jumlah Bimbingan</label>
        <input type="number" name="jumlah_bimbingan" class="form-control" min="0" placeholder="Misal: 5" required>
    </div>

    <div class="mb-3">
        <label>Upload Proposal</label>
        <input type="file" name="file_proposal" class="form-control" required>
    </div>

    <button class="btn btn-primary" type="submit">Daftar Seminar Proposal</button>
</form>
