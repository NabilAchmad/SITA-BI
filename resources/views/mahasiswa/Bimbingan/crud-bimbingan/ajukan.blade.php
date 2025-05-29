<div class="card shadow-lg border-0 bg-light bg-opacity-75" style="backdrop-filter: blur(10px);">
    <div class="card-header bg-dark text-white rounded-top">
        <h4 class="mb-0">Ajukan Bimbingan</h4>
    </div>
    <div class="card-body px-4 py-3">
        <form action="/submit-bimbingan" method="POST">
            @csrf

            <!-- Tanggal -->
            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control shadow-sm" required>
            </div>

            <!-- Waktu -->
            <div class="mb-3">
                <label for="waktu" class="form-label fw-semibold">Waktu</label>
                <input type="time" id="waktu" name="waktu" class="form-control shadow-sm" required>
            </div>

            <!-- Topik -->
            <div class="mb-3">
                <label for="topik" class="form-label fw-semibold">Topik</label>
                <textarea id="topik" name="topik" rows="4" class="form-control shadow-sm" required></textarea>
            </div>

            <!-- Tombol -->
            <button type="submit" class="btn btn-dark w-100 fw-semibold shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Ajukan
            </button>
        </form>
    </div>
</div>
