<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">Penugasan Bimbingan</li>

        <li class="breadcrumb-item {{ request()->routeIs('jurusan.penugasan-pembimbing.index') ? 'active' : '' }}">
            @if (request()->routeIs('jurusan.penugasan-pembimbing.index'))
                <span class="text-primary">Mahasiswa Belum Punya Pembimbing</span>
            @else
                <a href="{{ route('jurusan.penugasan-pembimbing.index') }}" class="text-dark">Mahasiswa Belum Punya
                    Pembimbing</a>
            @endif
        </li>

        <li class="breadcrumb-item {{ request()->routeIs('jurusan.penugasan-pembimbing.sudah') ? 'active' : '' }}">
            @if (request()->routeIs('jurusan.penugasan-pembimbing.sudah'))
                <span class="text-primary">Mahasiswa Sudah Punya Pembimbing</span>
            @else
                <a href="{{ route('jurusan.penugasan-pembimbing.sudah') }}" class="text-dark">Mahasiswa Sudah Punya Pembimbing</a>
            @endif
        </li>
    </ol>
</nav>
