@extends('layouts.template.main')

@section('title', 'Kelola Akun Dosen')
@section('content')
    {{-- File ini menampilkan tabel dan modal untuk CRUD Dosen --}}
    @include('admin.kelola-akun.dosen.crud-dosen.read')
@endsection

@push('scripts')
    {{-- 
        ======================================================================
        PERBAIKAN SCRIPT
        ======================================================================
        - URL tidak lagi di-hardcode. Sekarang diambil dari atribut 'data-url' pada tombol.
        - Beberapa blok $(document).ready() digabung menjadi satu agar lebih efisien.
    --}}
    <script>
        $(document).ready(function() {

            // --- SCRIPT UNTUK HAPUS DATA ---
            $(document).on("click", ".btn-hapus", function(e) {
                e.preventDefault();

                // ✅ PERBAIKAN: Ambil URL dari atribut data-url pada tombol hapus
                const url = $(this).data("url");

                swal({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Batal",
                            visible: true,
                            className: "btn btn-danger",
                        },
                        confirm: {
                            text: "Ya, hapus!",
                            className: "btn btn-success",
                        },
                    },
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: url, // ✅ Menggunakan URL dinamis
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"),
                            },
                            success: function(res) {
                                swal({
                                    title: "Berhasil!",
                                    text: "Data telah dihapus.",
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            text: "OK",
                                            className: "btn btn-primary"
                                        }
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                swal("Gagal!",
                                "Terjadi kesalahan saat menghapus data.", {
                                    icon: "error",
                                    buttons: false,
                                    timer: 2000,
                                });
                            },
                        });
                    } else {
                        swal("Dibatalkan", "Data Anda tetap aman!", {
                            icon: "info",
                            timer: 1500,
                            buttons: false,
                        });
                    }
                });
            });

            // --- SCRIPT UNTUK MENAMPILKAN MODAL EDIT ---
            $('#editAkunModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);

                // ✅ PERBAIKAN: Ambil URL dari atribut data-url pada tombol edit
                var url = button.data('url');
                var nama = button.data('nama');
                var email = button.data('email');
                var nidn = button.data('nidn');

                var modal = $(this);

                // ✅ Mengatur action form dengan URL dinamis
                modal.find('form').attr('action', url);

                // Isi input form modal
                modal.find('#editNama').val(nama);
                modal.find('#editEmail').val(email);
                modal.find('#editNidn').val(nidn);

                // Kosongkan password tiap buka modal edit
                modal.find('#editPassword').val('');
                modal.find('#editPasswordConfirmation').val('');
            });

            // --- SCRIPT UNTUK MENAMPILKAN MODAL DETAIL ---
            $('.btn-detail').on('click', function() {
                let nama = $(this).data('nama');
                let email = $(this).data('email');
                let nidn = $(this).data('nidn');
                let foto = $(this).data('foto');
                let created = $(this).data('created');
                let updated = $(this).data('updated');

                $('#detailNama').text(nama);
                $('#detailEmail').text(email);
                $('#detailNidn').text(nidn);
                $('#detailFoto').attr('src', foto);
                $('#detailCreated').text(created);
                $('#detailUpdated').text(updated);
            });

            // --- SCRIPT UNTUK MENAMPILKAN ALERT SUKSES DARI SESSION ---
            @if (session('success'))
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary"
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
