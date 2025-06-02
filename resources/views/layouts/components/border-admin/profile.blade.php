<li class="nav-item topbar-user dropdown hidden-caret">
    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
        <div class="avatar-sm">
            <img src="{{ $adminProfile->photo ? asset('storage/' . $adminProfile->photo) : asset('img/default-avatar.png') }}"
                alt="admin" class="avatar-img rounded-circle" />
        </div>
        <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">{{ $adminProfile->name }}</span>
        </span>
    </a>

    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <div class="dropdown-user-scroll scrollbar-outer">
            <li>
                <div class="user-box">
                    <div class="avatar-lg">
                        <img src="{{ $adminProfile->photo ? asset('storage/' . $adminProfile->photo) : asset('img/default-avatar.png') }}"
                            alt="image profile" class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                        <h4>{{ $adminProfile->name }}</h4>
                        <p class="text-muted">{{ $adminProfile->email }}</p>
                        <a href="{{ route('user.profile') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </div>
    </ul>
</li>
