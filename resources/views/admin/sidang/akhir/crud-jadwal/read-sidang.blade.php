@push('styles')
    <style>
        /* Fade-in untuk modal SweetAlert */
        .swal-modal.fade-in-modal {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
@endpush
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-danger"><i class="bi bi-calendar-x me-2"></i> Belum Punya Jadwal Sidang Akhir</h1>
            <p class="text-muted mb-0">Daftar mahasiswa yang telah terdaftar sidang akhir, namun belum memiliki jadwal.
            </p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Belum Dijadwalkan</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul TA</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $index => $mhs)
                            @php
                                $sidang = $mhs->tugasAkhir->sidang->firstWhere('status', 'dijadwalkan');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $mhs->user->name }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-success btn-jadwalkan"
                                        data-sidang-id="{{ $sidang->id }}" data-nama="{{ $mhs->user->name }}"
                                        data-judul="{{ $mhs->tugasAkhir->judul }}">
                                        <i class="bi bi-calendar-plus me-1"></i> Jadwalkan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i> Tidak ada mahasiswa yang menunggu
                                    penjadwalan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.sidang.akhir.modal.buat-penguji')
{{-- @include('admin.sidang.akhir.modal.buat-jadwal-sidang') --}}


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-jadwalkan');
                if (!btn) return;

                const sidangId = btn.dataset.sidangId;
                const template = document.getElementById('template-modal-penguji');

                if (!template) {
                    console.error('Template modal tidak ditemukan');
                    return;
                }

                const cloned = template.content.cloneNode(true);
                const wrapper = document.createElement('div');
                wrapper.appendChild(cloned);
                let htmlString = wrapper.innerHTML;

                htmlString = htmlString.replace(/jadwal-sidang\/simpan-penguji\/0/,
                    `jadwal-sidang/simpan-penguji/${sidangId}`);

                swal({
                    title: "Pilih Dosen Penguji",
                    content: {
                        element: "div",
                        attributes: {
                            innerHTML: htmlString
                        }
                    },
                    buttons: false,
                    closeOnClickOutside: false,
                    className: 'fade-in-modal'
                });

                setTimeout(() => {
                    const form = document.getElementById('form-penguji');
                    if (!form) return;

                    const checkboxes = form.querySelectorAll('input[name="penguji[]"]');
                    const searchInput = form.querySelector('#search-dosen');
                    const btnSimpan = form.querySelector('#btn-simpan-penguji');
                    const btnBatal = form.querySelector('#batal-penguji');

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', () => {
                            const totalChecked = [...checkboxes].filter(chk => chk
                                .checked).length;
                            if (totalChecked > 4) {
                                cb.checked = false;
                                swal("Maksimal 4 penguji!",
                                    "Silakan kurangi pilihan.", "warning");
                            }
                        });
                    });

                    searchInput.addEventListener('input', () => {
                        const filter = searchInput.value.toLowerCase();
                        form.querySelectorAll('.dosen-item').forEach(item => {
                            const name = item.querySelector('.nama-dosen')
                                ?.textContent.toLowerCase() || '';
                            item.style.display = name.includes(filter) ? '' :
                            'none';
                        });
                    });

                    btnBatal.addEventListener('click', () => {
                        swal.close();
                    });

                    btnSimpan.addEventListener('click', () => {
                        const formData = new FormData(form);
                        btnSimpan.disabled = true;
                        btnSimpan.innerText = 'Menyimpan...';

                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': formData.get('_token'),
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.status === 'success') {
                                    swal({
                                        title: "Berhasil!",
                                        text: res.message,
                                        icon: "success",
                                        buttons: false,
                                        timer: 1500
                                    });

                                    document.body.dispatchEvent(new CustomEvent(
                                        'pengujiDipilih', {
                                            detail: {
                                                urlForm: res.urlForm
                                            }
                                        }));
                                } else {
                                    swal("Gagal", res.message ||
                                        "Terjadi kesalahan saat menyimpan.", "error"
                                        );
                                }
                            })
                            .catch(() => {
                                swal("Gagal",
                                    "Terjadi kesalahan saat menghubungi server.",
                                    "error");
                            })
                            .finally(() => {
                                btnSimpan.disabled = false;
                                btnSimpan.innerText = 'Simpan';
                            });
                    });
                }, 100);
            });
        });
    </script>
@endpush
