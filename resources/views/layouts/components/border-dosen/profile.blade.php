<li class="nav-item topbar-user dropdown hidden-caret">
    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
        <div class="avatar-sm">
            <img src="{{ $userProfile->photo ? asset('storage/' . $userProfile->photo) : asset('assets/img/avatar-default.png') }}"
                alt="Avatar" class="avatar-img rounded-circle" />
        </div>
        <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">{{ $userProfile->name }}</span>
        </span>
    </a>

    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <div class="dropdown-user-scroll scrollbar-outer">
            <li>
                <div class="user-box">
                    <div class="avatar-lg">
                        <img src="{{ $userProfile->photo ? asset('storage/' . $userProfile->photo) : asset('assets/img/avatar-default.png') }}"
                            alt="Profile" class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                        <h4>{{ $userProfile->name }}</h4>
                        <p class="text-muted">
                            {{ ucfirst($userProfile->roles->first()->name ?? 'Mahasiswa') }}
                        </p>
                        <a href="{{ route('user.profile.dosen') }}" class="btn btn-xs btn-secondary btn-sm">View
                            Profile</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </div>
    </ul>
</li>
