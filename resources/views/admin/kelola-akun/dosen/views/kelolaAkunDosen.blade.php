@extends('layouts.template.main')

@section('title', 'Kelola Akun Dosen')
@section('content')
    @include('admin.kelola-akun.dosen.crud-dosen.read')
@endsection

@push('scripts')
    <!-- SweetAlert Script -->
    <script>
        $(document).ready(function() {
            $(document).on("click", ".btn-hapus", function(e) {
                e.preventDefault();

                const id = $(this).data("id");

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
                            url: "/admin/kelola-akun/dosen/hapus/" +
                                id, // Sesuaikan route prefix-mu
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
                                    location
                                        .reload(); // Reload halaman supaya data & pagination update
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
        });

        $(document).ready(function() {
            $('#editAkunModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // tombol yang klik
                var id = button.data('id');
                var nama = button.data('nama');
                var email = button.data('email');
                var nidn = button.data('nidn');

                var modal = $(this);

                // Set action form sesuai id dosen (gunakan URL absolut atau root path)
                modal.find('form').attr('action', '/admin/kelola-akun/dosen/update/' + id);

                // Isi input form modal
                modal.find('#editNama').val(nama);
                modal.find('#editEmail').val(email);
                modal.find('#editNidn').val(nidn);

                // Kosongkan password tiap buka modal edit
                modal.find('#editPassword').val('');
                modal.find('#editPasswordConfirmation').val('');
            });

            // SweetAlert sukses edit dari session
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

        $(document).ready(function() {
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
        });

        // Cek session success Laravel, lalu tampilkan alert
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
    </script>
@endpush
