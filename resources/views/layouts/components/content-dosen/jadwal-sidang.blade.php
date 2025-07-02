@if ($jadwalSidang->count())
    <div class="col-md-6">
        <div class="card shadow rounded-4 border-0">
            <div class="card-header bg-danger text-white rounded-top-4">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Jadwal Sidang Mendatang</h5>
            </div>
            <div class="card-body px-4 pt-3 pb-2">
                <ul class="list-unstyled mb-0">
                    @foreach ($jadwalSidang as $sidang)
                        <li class="mb-3 pb-2 border-bottom">
                            <strong>{{ $sidang->mahasiswa->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($sidang->tanggal_sidang)->format('d M Y H:i') }}
                            </small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
