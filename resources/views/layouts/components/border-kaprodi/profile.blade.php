<li class="nav-item topbar-user dropdown hidden-caret">
    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
        <div class="avatar-sm">
            <img src="{{ asset('assets/img/team/erland.jpg') }}" alt="Profile"
                class="avatar-img rounded-circle" />
        </div>
        <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">{{ Auth::user()->name ?? 'User' }}</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <div class="dropdown-user-scroll scrollbar-outer">
            <li>
                <div class="user-box">
                    <div class="avatar-lg">
                        <img src="{{ asset('assets/img/team/nabil.jpg') }}" alt="image profile"
                            class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                        <h4>{{ Auth::user()->name ?? 'Nama Tidak Ada' }}</h4>
                        <p class="text-muted">{{ Auth::user()->email ?? 'Email Tidak Ada' }}</p>
                        <a href="{{ url('/admin/profile') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown-divider"></div>
                <form id="logout-form" action="{{ route('kaprodi.logout') }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </li>
        </div>
    </ul>
</li>
