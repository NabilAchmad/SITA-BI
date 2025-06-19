<!-- Search & Sort Controls -->
<div class="row g-2 mb-3 align-items-center">
    <div class="col-12 col-md-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari pengumuman...">
    </div>
    <div class="col-6 col-md-2">
        <select id="sortSelect" class="form-select">
            <option value="desc">Terbaru</option>
            <option value="asc">Terlama</option>
        </select>
    </div>
    <div class="col-6 col-md-3">
        <select id="audiensFilter" class="form-select">
            <option value="">Semua Audiens</option>
            <option value="registered_users">Pengguna Terdaftar</option>
            <option value="dosen">Dosen</option>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="guest">Tamu</option>
        </select>
    </div>
    <div class="col-12 col-md-3">
        <button type="button" id="resetFilter" class="btn btn-secondary w-100">Reset Filter</button>
    </div>
</div>
