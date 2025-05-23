<div class="container">
    <h1 class="mb-4">Log Aktivitas Sistem</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Aksi</th>
                <th>Objek</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->user->name ?? 'Tidak diketahui' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->object_type }} #{{ $log->object_id }}</td>
                    <td>{{ $log->deskripsi }}</td>
                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada log aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
