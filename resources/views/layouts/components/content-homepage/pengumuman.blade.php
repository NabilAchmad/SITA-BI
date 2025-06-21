<section id="pengumuman" class="pengumuman section pt-4">
    <!-- Section Title -->
    <div class="container section-title text-center" data-aos="fade-up" style="margin-bottom: 30px;">
        <h1>Pengumuman</h1>
    </div>

    <div class="container">
        <div class="row justify-content-center gy-4">

            @forelse($pengumuman as $item)
                @if (in_array($item->audiens, ['all_users', 'guest']))
                    <div class="col-12 col-md-10 col-lg-8 mb-4" data-aos="fade-up">
                        <div class="card h-100 border-0 shadow"
                            style="background-color: #f0f8ff; border-radius: 18px; transition: 0.3s ease;">
                            <div class="card-body p-4 p-md-5">
                                <h5 class="card-title mb-3"
                                    style="color: #0d6efd; font-weight: 600; font-size: 1.5rem;">
                                    {{ $item->judul }}
                                </h5>
                                <p class="card-text" style="color: #333; font-size: 16px; line-height: 1.6;">
                                    {{ $item->isi }}
                                </p>
                            </div>
                            <div class="card-footer border-0 bg-transparent text-end px-4 pb-3"
                                style="color: #0d6efd; font-size: 14px;">
                                Oleh: {{ $item->pembuat->name ?? 'Admin' }}
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-megaphone fs-1 mb-3 d-block"></i>
                    <p class="mb-0">Belum ada pengumuman untuk ditampilkan.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>

@push('styles')
    <style>
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>
@endpush
