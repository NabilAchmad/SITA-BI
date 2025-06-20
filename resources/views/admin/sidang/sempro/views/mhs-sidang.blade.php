@extends('layouts.template.main')

@section('title', 'List Mahasiswa Sidang')

@section('content')
    @include('admin.sidang.sempro.crud-jadwal.read-sidang')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalContainer = document.getElementById('modalContainer');
            let currentPengujiModal = null;
            let currentJadwalModal = null;

            function limitCheckboxSelection() {
                const checkboxes = currentPengujiModal.querySelectorAll('input[type=checkbox][name="penguji[]"]');
                checkboxes.forEach(chk => {
                    chk.addEventListener('change', () => {
                        const checkedCount = [...checkboxes].filter(c => c.checked).length;
                        if (checkedCount > 4) {
                            chk.checked = false;
                            swal({
                                title: "Peringatan!",
                                text: "Anda hanya dapat memilih maksimal 4 penguji.",
                                icon: "warning",
                                buttons: {
                                    confirm: {
                                        text: "OK",
                                        className: "btn btn-primary"
                                    }
                                }
                            });
                        }
                    });
                });
            }

            function setupSearchFilter() {
                const searchInput = currentPengujiModal.querySelector('#search-dosen');
                const tbody = currentPengujiModal.querySelector('#tbody-dosen');
                searchInput.addEventListener('input', () => {
                    const val = searchInput.value.toLowerCase();
                    tbody.querySelectorAll('tr.dosen-item').forEach(row => {
                        const name = row.querySelector('.nama-dosen').textContent.toLowerCase();
                        row.style.display = name.includes(val) ? '' : 'none';
                    });
                });
            }

            // Modal Penguji
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-jadwalkan')) {
                    const btn = e.target.closest('.btn-jadwalkan');

                    modalContainer.innerHTML = '';
                    const templatePenguji = document.getElementById('template-modal-penguji');
                    const clone = templatePenguji.content.cloneNode(true);
                    modalContainer.appendChild(clone);

                    currentPengujiModal = document.getElementById('modalPenguji');

                    const formPenguji = document.getElementById('form-penguji');
                    const sidangId = btn.dataset.sidangId;
                    formPenguji.action = btn.dataset.url;

                    const bsModalPenguji = new bootstrap.Modal(currentPengujiModal);
                    bsModalPenguji.show();

                    limitCheckboxSelection();
                    setupSearchFilter();

                    currentPengujiModal.querySelector('#batal-penguji').addEventListener('click', () => {
                        bsModalPenguji.hide();
                    });

                    formPenguji.addEventListener('submit', function(e) {
                        e.preventDefault();

                        let formData = new FormData(formPenguji);

                        fetch(formPenguji.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(async response => {
                                if (!response.ok) {
                                    if (response.status === 422) {
                                        const errorData = await response.json();
                                        const messages = Object.values(errorData.errors)
                                            .flat().join('\n');
                                        swal("Validasi gagal", messages, "warning");
                                    } else {
                                        swal("Error", "Terjadi kesalahan server.", "error");
                                    }
                                    throw new Error('Fetch error');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    bsModalPenguji.hide();
                                    openModalJadwalSidang({
                                        sidang_id: sidangId,
                                        nama: btn.dataset.nama,
                                        nim: btn.dataset.nim,
                                        judul: btn.dataset.judul
                                    });
                                } else {
                                    swal("Gagal", data.message || 'Gagal menyimpan penguji.',
                                        "error");
                                }
                            })
                            .catch(err => {
                                if (err.message !== 'Fetch error') {
                                    swal("Error", "Terjadi kesalahan saat menyimpan penguji.",
                                        "error");
                                }
                            });
                    });
                }
            });

            // Modal Jadwal Sidang
            function openModalJadwalSidang({
                sidang_id,
                nama,
                nim,
                judul
            }) {
                modalContainer.innerHTML = '';
                const templateJadwal = document.getElementById('template-modal-jadwal-sidang');
                const clone = templateJadwal.content.cloneNode(true);
                modalContainer.appendChild(clone);

                currentJadwalModal = document.getElementById('modalJadwalSidang');

                document.getElementById('jadwal-sidang_id').value = sidang_id;
                document.getElementById('jadwal-nama').value = nama;
                document.getElementById('jadwal-nim').value = nim;
                document.getElementById('jadwal-judul').value = judul;

                const formJadwal = document.getElementById('form-jadwal-sidang');
                const bsModalJadwal = new bootstrap.Modal(currentJadwalModal);
                bsModalJadwal.show();

                let isSubmitting = false;

                formJadwal.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (isSubmitting) return;
                    isSubmitting = true;

                    let formData = new FormData(formJadwal);

                    fetch(formJadwal.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            isSubmitting = false;
                            if (!response.ok) {
                                if (response.status === 422) {
                                    const errorData = await response.json();
                                    const messages = Object.values(errorData.errors).flat().join(
                                        '\n');
                                    swal("Validasi gagal", messages, "warning");
                                } else {
                                    swal("Error", "Terjadi kesalahan server.", "error");
                                }
                                throw new Error('Fetch error');
                            }

                            try {
                                const data = await response.json();
                                if (data.success) {
                                    bsModalJadwal.hide();
                                    swal({
                                        title: "Berhasil!",
                                        text: data.message ||
                                            "Jadwal sidang berhasil dibuat.",
                                        icon: "success",
                                        buttons: {
                                            confirm: {
                                                text: "OK",
                                                className: "btn btn-primary"
                                            }
                                        }
                                    }).then(() => {
                                        window.location.href =
                                            "{{ route('sidang.kelola.sempro') }}";
                                    });
                                } else {
                                    swal("Gagal", data.message || 'Gagal menyimpan jadwal sidang.',
                                        "error");
                                }
                            } catch (e) {
                                swal("Error", "Respons tidak valid dari server.", "error");
                            }
                        })
                        .catch(err => {
                            isSubmitting = false;
                            if (err.message !== 'Fetch error') {
                                swal("Error", "Terjadi kesalahan saat menyimpan jadwal.", "error");
                            }
                        });
                });
            }

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
