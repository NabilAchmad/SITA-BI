@extends('layouts.template.main')
@section('title', 'Kelola Tawaran Topik')
@section('content')

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h4 class="card-title text-center text-primary mb-0">Kelola Tawaran Topik & Pengajuan</h4>
        </div>
        <div class="card-body">
            <!-- Navigasi Tab -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !request('tab') || request('tab') == 'topik' ? 'active' : '' }}"
                        id="topik-tab" data-bs-toggle="tab" data-bs-target="#topik-pane" type="button" role="tab"
                        aria-controls="topik-pane" aria-selected="true">Tawaran Topik Saya</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'mahasiswa' ? 'active' : '' }}" id="mahasiswa-tab"
                        data-bs-toggle="tab" data-bs-target="#mahasiswa-pane" type="button" role="tab"
                        aria-controls="mahasiswa-pane" aria-selected="false">Pengajuan Mahasiswa</button>
                </li>
            </ul>

            <!-- Konten Tab -->
            <div class="tab-content" id="myTabContent">
                <!-- Tab 1: Daftar Tawaran Topik -->
                <div class="tab-pane fade {{ !request('tab') || request('tab') == 'topik' ? 'show active' : '' }}"
                    id="topik-pane" role="tabpanel" aria-labelledby="topik-tab" tabindex="0">
                    <div class="py-3">
                        {{-- Harusnya ini untuk "Tawaran Topik Saya" --}}
                        @include('dosen.tawaran-topik.partials.tab-tawaran-topik')
                    </div>
                </div>

                <!-- Tab 2: Daftar Pengajuan Mahasiswa -->
                <div class="tab-pane fade {{ request('tab') == 'mahasiswa' ? 'show active' : '' }}" id="mahasiswa-pane"
                    role="tabpanel" aria-labelledby="mahasiswa-tab" tabindex="0">
                    <div class="py-3">
                        {{-- Harusnya ini untuk "Pengajuan Mahasiswa" --}}
                        @include('dosen.tawaran-topik.partials.tab-pengajuan-mahasiswa')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include semua modal --}}
    @include('dosen.tawaran-topik.modal.create')
    @include('dosen.tawaran-topik.modal.edit')
    @include('dosen.tawaran-topik.modal.delete')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Skrip untuk menjaga tab tetap aktif setelah reload halaman atau filter
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set("tab", $(this).attr("aria-controls").replace('-pane', ''));
                // Hapus parameter paginasi lain agar tidak konflik
                newUrl.searchParams.delete('page');
                newUrl.searchParams.delete('mahasiswa_page');
                window.history.replaceState({
                    path: newUrl.href
                }, '', newUrl.href);
            });

            // Skrip untuk Modal Edit
            $(document).on('click', '.btn-edit', function() {
                var button = $(this);
                var action = button.data('action');
                var judul = button.data('judul');
                var deskripsi = button.data('deskripsi');
                var kuota = button.data('kuota');

                var modal = $('#editTawaranTopikModal');
                modal.find('form').attr('action', action);
                modal.find('#edit_judul_topik').val(judul);
                modal.find('#edit_deskripsi').val(deskripsi);
                modal.find('#edit_kuota').val(kuota);
            });

            // Skrip untuk Modal Delete
            $(document).on("click", ".btn-hapus", function() {
                let deleteUrl = $(this).data('url');
                $('#formHapusTawaranTopik').attr('action', deleteUrl);
            });
        });
    </script>
@endpush
