@extends('layouts.template.main')

@section('title', 'List Mahasiswa Sidang')

@section('content')
    @include('admin.sidang.akhir.crud-jadwal.read-sidang')
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
                            alert('Maksimal 4 dosen penguji.');
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
            document.querySelectorAll('.btn-jadwalkan').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalContainer.innerHTML = '';
                    const templatePenguji = document.getElementById('template-modal-penguji');
                    const clone = templatePenguji.content.cloneNode(true);
                    modalContainer.appendChild(clone);

                    currentPengujiModal = document.getElementById('modalPenguji');

                    const formPenguji = document.getElementById('form-penguji');
                    const sidangId = this.dataset.sidangId;
                    formPenguji.action = this.dataset.url;

                    const bsModalPenguji = new bootstrap.Modal(currentPengujiModal);
                    bsModalPenguji.show();

                    limitCheckboxSelection();
                    setupSearchFilter();

                    currentPengujiModal.querySelector('#batal-penguji').addEventListener('click',
                    () => {
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
                                        const messages = Object.values(errorData
                                            .errors).flat().join('\n');
                                        alert('Validasi gagal:\n' + messages);
                                    } else {
                                        alert('Terjadi kesalahan server.');
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
                                    alert(data.message || 'Gagal menyimpan penguji.');
                                }
                            })
                            .catch(err => {
                                // Kalau error sudah ditangani, ini fallback
                                if (err.message !== 'Fetch error') {
                                    alert('Terjadi kesalahan saat menyimpan penguji.');
                                }
                            });
                    }, {
                        once: true
                    });
                });
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

                formJadwal.addEventListener('submit', function(e) {
                    e.preventDefault();

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
                            if (!response.ok) {
                                if (response.status === 422) {
                                    const errorData = await response.json();
                                    const messages = Object.values(errorData.errors).flat().join(
                                        '\n');
                                    alert('Validasi gagal:\n' + messages);
                                } else {
                                    alert('Terjadi kesalahan server.');
                                }
                                throw new Error('Fetch error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                bsModalJadwal.hide();

                                swal({
                                    title: "Berhasil!",
                                    text: data.message || "Jadwal sidang berhasil dibuat.",
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            text: "OK",
                                            className: "btn btn-primary"
                                        }
                                    }
                                }).then(() => {
                                    window.location.href = "{{ route('jadwal.sidang.akhir') }}";
                                });
                            } else {
                                alert(data.message || 'Gagal menyimpan jadwal sidang.');
                            }
                        })
                        .catch(err => {
                            if (err.message !== 'Fetch error') {
                                alert('Terjadi kesalahan saat menyimpan jadwal.');
                            }
                        });
                }, {
                    once: true
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
