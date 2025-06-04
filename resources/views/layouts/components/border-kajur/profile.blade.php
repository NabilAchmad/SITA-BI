<li class="nav-item topbar-user dropdown hidden-caret">
    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
        aria-expanded="false">
        <div class="avatar-sm">
            <img src="{{ asset('assets/img/team/erland.jpg') }}" alt="Erland"
                class="avatar-img rounded-circle" />
        </div>
        <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">Ketua Jurusan</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <li>
            <div class="dropdown-user-scroll scrollbar-outer">
                <div class="user-box">
                    <div class="avatar-lg">
                        <img src="{{ asset('assets/img/team/nabil.jpg') }}" alt="image profile"
                            class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                        <h4>kajur_username</h4>
                        <p class="text-muted">kajur_email@example.com</p>
                        <a href="{{ url('/kajur/profile') }}"
                            class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('kajur.logout')}}">Logout</a>
            </div>
        </li>
    </ul>
</li>
