
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Ajukan Bimbingan</h4>
        </div>
        <div class="card-body">
            <form action="/submit-bimbingan" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="waktu" class="form-label">Waktu:</label>
                    <input type="time" id="waktu" name="waktu" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="topik" class="form-label">Topik:</label>
                    <textarea id="topik" name="topik" rows="4" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajukan</button>
            </form>
        </div>
    </div>