<!-- Modal Edit Pembimbing -->
<div class="modal fade" id="modalEditPembimbing-{{ $mhs->id }}" tabindex="-1"
    aria-labelledby="modalLabel-{{ $mhs->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="modalLabel-{{ $mhs->id }}">Edit Pembimbing - {{ $mhs->user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('pembimbing.update', $mhs->tugasAkhir->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="mb-3">
                        <input type="text" id="searchDosenEdit-{{ $mhs->id }}" class="form-control"
                            placeholder="Cari nama dosen...">
                    </div>

                    <table class="table table-bordered table-hover shadow-sm">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDosenEdit-{{ $mhs->id }}" class="text-center">
                            @foreach ($dosen as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="nama-dosen">{{ $d->user->name }}</td>
                                    <td>{{ $d->nidn }}</td>
                                    <td>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input fs-5 me-2" type="radio" name="pembimbing1"
                                                value="{{ $d->id }}"
                                                @if (optional($mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing1')->first())->dosen_id == $d->id) checked @endif>
                                            <span class="me-3">P1</span>

                                            <input class="form-check-input fs-5 ms-2" type="radio" name="pembimbing2"
                                                value="{{ $d->id }}"
                                                @if (optional($mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing2')->first())->dosen_id == $d->id) checked @endif>
                                            <span class="ms-2">P2</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted fst-italic">* Pilih 1 dosen untuk masing-masing pembimbing</span>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
