@extends('layouts.template.main')

@section('title', 'Revisi Tugas Akhir')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Revisi Tugas Akhir</h3>

    {{-- Form Upload Revisi --}}
    <form action="{{ route('ta.revisi.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Upload File Revisi (PDF)</label>
            <input type="file" name="file" id="file" class="form-control" required>
            @error('file')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    {{-- List Revisi --}}
    <h5>Riwayat Revisi</h5>
    <ul class="list-group">
        @forelse ($revisi as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ basename($item->file) }}</span>
                <small class="text-muted">{{ $item->uploaded_at->format('d M Y H:i') }}</small>
                <a href="{{ Storage::url($item->file) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Lihat</a>
            </li>
        @empty
            <li class="list-group-item text-muted">Belum ada revisi yang diunggah.</li>
        @endforelse
    </ul>
</div>
@endsection
