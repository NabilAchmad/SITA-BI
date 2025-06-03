<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Bimbingan</li>

        <li class="breadcrumb-item">
            @if (request()->routeIs('akun-dosen.kelola'))
                <span class="text-primary">Ajukan Jadwal Bimbingan</span>
            @else
                <a href="{{ route('akun-dosen.kelola') }}" class="text-dark">Ajukan Jadwal Bimbingan</a>
            @endif
        </li>
    </ol>
</nav>