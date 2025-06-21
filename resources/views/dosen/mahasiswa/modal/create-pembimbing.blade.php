{{-- Modal Pilih Pembimbing --}}
<div class="modal fade" id="modalPembimbing-{{ $mhs->id }}" tabindex="-1"
    aria-labelledby="modalLabel-{{ $mhs->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white"> <!-- warna abu-abu gelap -->
                <h5 class="modal-title" id="modalLabel-{{ $mhs->id }}">
                    Pilih Dosen Pembimbing untuk {{ $mhs->user->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penugasan-bimbingan.store', $mhs->id) }}" method="POST"
                class="form-pilih-pembimbing">
                @csrf
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">

                    {{-- Pencarian realtime --}}
                    <div class="mb-3">
                        <input type="text" id="searchDosen-{{ $mhs->id }}" class="form-control"
                            placeholder="Cari nama dosen...">
                    </div>

                    <table class="table table-bordered table-hover shadow rounded">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Dosen</th>
                                <th style="width: 150px;">NIDN</th>
                                <th style="width: 100px;">Pilih</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="tbodyDosen-{{ $mhs->id }}">
                            @foreach ($dosen as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="nama-dosen">{{ $item->user->name }}</td>
                                    <td>{{ $item->nidn }}</td>
                                    <td>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input fs-5" type="checkbox" name="pembimbing[]"
                                                value="{{ $item->id }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted fst-italic">* Maksimal pilih 2 dosen pembimbing</span>
                    <button type="submit" class="btn btn-success">Simpan Pilihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script pencarian realtime dan checkbox limit --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchDosen-{{ $mhs->id }}');
        const tbody = document.getElementById('tbodyDosen-{{ $mhs->id }}');
        const checkboxes = tbody.querySelectorAll('input[name="pembimbing[]"]');

        // Realtime filter dosen berdasarkan nama
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            tbody.querySelectorAll('tr').forEach(function(row) {
                const nama = row.querySelector('.nama-dosen').textContent.toLowerCase();
                if (nama.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Batasi maksimal 2 checkbox yang dipilih
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const checked = tbody.querySelectorAll('input[name="pembimbing[]"]:checked');
                if (checked.length > 2) {
                    this.checked = false;
                    alert('Maksimal hanya bisa memilih 2 dosen pembimbing.');
                }
            });
        });
    });
</script>
