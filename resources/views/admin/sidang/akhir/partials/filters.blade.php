{{-- Navigasi Tab Program Studi --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
            href="{{ route('sidang.kelola.akhir') }}">Semua Prodi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
            href="{{ route('sidang.kelola.akhir', ['prodi' => 'D4']) }}">D4 Bahasa Inggris</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
            href="{{ route('sidang.kelola.akhir', ['prodi' => 'D3']) }}">D3 Bahasa Inggris</a>
    </li>
</ul>

{{-- Form Pencarian --}}
<form method="GET" action="{{ route('sidang.kelola.akhir') }}">
    <div class="input-group mb-3">
        <input type="hidden" name="prodi" value="{{ request('prodi') }}">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="search" class="form-control"
            placeholder="Cari berdasarkan nama atau NIM mahasiswa..." value="{{ request('search') }}"
            autocomplete="off">
        <button class="btn btn-primary" type="submit">Cari</button>
    </div>
</form>
