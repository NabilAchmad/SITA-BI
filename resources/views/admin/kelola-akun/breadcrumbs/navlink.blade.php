<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Kelola Akun</li>

        <li class="breadcrumb-item">
            @if (request()->routeIs('akun-dosen.kelola'))
                <span class="text-primary">Dosen</span>
            @else
                <a href="{{ route('akun-dosen.kelola') }}" class="text-dark">Dosen</a>
            @endif
        </li>

        <li class="breadcrumb-item">
            @if (request()->routeIs('akun-mahasiswa.kelola'))
                <span class="text-primary">Mahasiswa</span>
            @else
                <a href="{{ route('akun-mahasiswa.kelola') }}" class="text-dark">Mahasiswa</a>
            @endif
        </li>
    </ol>
</nav>
