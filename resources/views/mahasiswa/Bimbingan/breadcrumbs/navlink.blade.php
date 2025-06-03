<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            @if (request()->routeIs('dashboard.bimbingan'))
                <span class="text-primary">Dashboard</span>
            @else
                <a href="{{ route('dashboard.bimbingan') }}" class="text-dark">Dashboard</a>
            @endif
        </li>

        <li class="breadcrumb-item">
            @if (request()->routeIs('bimbingan.ajukanJadwal'))
                <span class="text-primary">Ajukan Jadwal Bimbingan</span>
            @else
                <a href="{{ route('bimbingan.ajukanJadwal') }}" class="text-dark">Ajukan Jadwal Bimbingan</a>
            @endif
        </li>
    </ol>
</nav>