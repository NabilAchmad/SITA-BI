<template id="template-modal-penguji">
    <form action="{{ route('jadwal-sidang.simpanPenguji', ['sidang_id' => 0]) }}" method="POST" id="form-penguji">
        @csrf
        <div class="modal-body p-0">
            <div class="p-4" style="max-height: 70vh; overflow-y: auto; min-width: 700px;">
                <h5 class="fw-bold mb-3">Pilih Dosen Penguji</h5>

                <div class="mb-3">
                    <input type="text" class="form-control" id="search-dosen" placeholder="Cari nama dosen...">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover rounded overflow-hidden">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Dosen</th>
                                <th style="width: 150px;">NIDN</th>
                                <th style="width: 100px;">Pilih</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="tbody-dosen">
                            @foreach ($dosen as $index => $item)
                                <tr class="dosen-item align-middle">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="nama-dosen">{{ $item->user->name }}</td>
                                    <td>{{ $item->nidn }}</td>
                                    <td>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input fs-5" type="checkbox" name="penguji[]"
                                                value="{{ $item->id }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted fst-italic">* Maksimal pilih 4 dosen penguji</span>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" id="batal-penguji">Batal</button>
                        <button type="button" class="btn btn-primary" id="btn-simpan-penguji">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
