@php
    // Ambil data user yang sedang login sekali saja untuk efisiensi.
    $currentUser = auth()->user();

    // Siapkan variabel default untuk mencegah error.
    $profilePhoto = asset('img/default-avatar.png'); // Foto default
    $profileName = 'Guest';
    $profileEmail = '';
    $profileRoute = '#';

    if ($currentUser) {
        $profileName = $currentUser->name;
        $profileEmail = $currentUser->email;

        // Tentukan foto profil berdasarkan relasi yang ada.
        if ($currentUser->hasRole('admin') && $currentUser->photo) {
            $profilePhoto = asset('storage/' . $currentUser->photo);
            $profileRoute = route('admin.profile');
        } elseif (
            $currentUser->hasAnyRole(['dosen', 'kajur', 'kaprodi-d3', 'kaprodi-d4']) &&
            $currentUser->dosen?->photo
        ) {
            $profilePhoto = asset('storage/' . $currentUser->dosen->photo);
            $profileRoute = route('dosen.profile');
        } elseif ($currentUser->hasRole('mahasiswa') && $currentUser->mahasiswa?->photo) {
            $profilePhoto = asset('storage/' . $currentUser->mahasiswa->photo);
            $profileRoute = route('mahasiswa.profile');
        } elseif ($currentUser->photo) {
            // Fallback jika tidak ada foto spesifik di relasi, tapi ada di user.
            $profilePhoto = asset('storage/' . $currentUser->photo);
        }

        // Tentukan rute profil berdasarkan peran.
        if ($currentUser->hasRole('admin')) {
            $profileRoute = route('admin.profile');
        } elseif ($currentUser->hasAnyRole(['dosen', 'kajur', 'kaprodi-d3', 'kaprodi-d4'])) {
            $profileRoute = route('dosen.profile');
        } elseif ($currentUser->hasRole('mahasiswa')) {
            $profileRoute = route('mahasiswa.profile');
        }
    }
@endphp

<div class="main-header">
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                <!-- Notification (Bisa dibuat dinamis di kemudian hari) -->
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        {{-- <span class="notification">4</span> --}}
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">
                                Anda tidak memiliki notifikasi baru
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- End Notification -->

                <!-- User Profile Dropdown -->
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                        aria-expanded="false">
                        <div class="avatar-sm">
                            {{-- ✅ PERBAIKAN: Menggunakan variabel dinamis $profilePhoto --}}
                            <img src="{{ $profilePhoto }}" alt="profile" class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            {{-- ✅ PERBAIKAN: Menggunakan variabel dinamis $profileName --}}
                            <span class="fw-bold">{{ $profileName }}</span>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        {{-- ✅ PERBAIKAN: Menggunakan variabel dinamis $profilePhoto --}}
                                        <img src="{{ $profilePhoto }}" alt="image profile" class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        {{-- ✅ PERBAIKAN: Menggunakan variabel dinamis --}}
                                        <h4>{{ $profileName }}</h4>
                                        <p class="text-muted">{{ $profileEmail }}</p>
                                        <a href="{{ $profileRoute }}" class="btn btn-xs btn-secondary btn-sm">View
                                            Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                {{-- Form Logout selalu sama untuk semua peran --}}
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
                <!-- End User Profile Dropdown -->
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
