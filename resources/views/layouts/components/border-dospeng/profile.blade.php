<li class="nav-item topbar-user dropdown hidden-caret">
    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
        aria-expanded="false">
        <div class="avatar-sm">
            <img src="{{ asset('assets/img/team/erland.jpg') }}" alt="Erland"
                class="avatar-img rounded-circle" />
        </div>
        <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">Erland Agsya Agustian</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-user animated fadeIn">
        <div class="dropdown-user-scroll scrollbar-outer">
            <li>
                <div class="user-box">
                    <div class="avatar-lg">
                        <img src="{{ asset('assets/img/team/erland.jpg') }}" alt="image profile"
                            class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                        <h4>erlaaaand_</h4>
                        <p class="text-muted">erlandagsya2@gmail.com</p>
                        <a href="{{ url('/admin/profile') }}"
                            class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Logout</a>
            </li>
        </div>
    </ul>
</li>