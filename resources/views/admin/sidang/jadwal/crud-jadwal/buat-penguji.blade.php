<h1 class="mb-4 fw-bold text-primary text-center border-bottom pb-3">Pilih Dosen Penguji</h1>

<form action="{{ route('jadwal-sidang.simpanPenguji', $sidang->id) }}" method="POST">
    @csrf

    <table class="table table-bordered table-hover shadow rounded">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Nama Dosen</th>
                <th>NIDN</th>
                <th>Pilih</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($dosen as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
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

    <div class="d-flex justify-content-between align-items-center mt-4 px-1">
        <p class="text-muted fst-italic mb-0">* Silakan pilih maksimal 4 dosen penguji.</p>
        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">Pilih</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="penguji[]"]');
        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', function() {
                const checked = document.querySelectorAll('input[name="penguji[]"]:checked');
                if (checked.length > 4) {
                    this.checked = false;
                    alert('Maksimal hanya bisa memilih 4 dosen penguji.');
                }
            });
        });
    });
</script>
