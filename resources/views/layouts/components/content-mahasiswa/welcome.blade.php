<div class="card shadow-lg border-0 rounded-4 mb-4"
    style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
    <div class="card-body d-flex align-items-center">
        <div class="me-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($namaMahasiswa) }}&background=0D8ABC&color=fff&size=64"
                alt="Avatar" class="rounded-circle shadow-sm" width="64" height="64">
        </div>
        <div>
            <h5 class="card-title mb-1 fw-bold text-primary">
                Selamat Datang, {{ $namaMahasiswa }}!
            </h5>
            <p class="card-text text-muted mb-0">
                Semoga harimu menyenangkan dan penuh semangat untuk terus belajar dan berkembang.
            </p>
        </div>
    </div>
</div>
