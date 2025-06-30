<?php

namespace App\Services\Dosen;

use App\Models\Dosen;
use App\Models\TawaranTopik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class TawaranTopikService
{
    protected Dosen $dosen;

    public function __construct()
    {
        $this->dosen = Auth::user()->dosen;
    }

    /**
     * PERBAIKAN: Mengambil tawaran topik dengan paginasi, bukan collection.
     * Mengubah ->get() menjadi ->paginate().
     */
    public function getActiveTopics(int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->dosen->topik()->latest()->paginate($perPage);
    }

    /**
     * PERBAIKAN: Mengambil topik yang dihapus dengan paginasi.
     */
    public function getTrashedTopics(int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->dosen->topik()->onlyTrashed()->latest()->paginate($perPage);
    }

    /**
     * Membuat tawaran topik baru.
     */
    public function createTopic(array $validatedData): TawaranTopik
    {
        return $this->dosen->topik()->create($validatedData);
    }

    /**
     * Memperbarui data tawaran topik.
     */
    public function updateTopic(TawaranTopik $tawaranTopik, array $validatedData): bool
    {
        return $tawaranTopik->update($validatedData);
    }

    /**
     * Menghapus (soft delete) tawaran topik.
     */
    public function deleteTopic(TawaranTopik $tawaranTopik): ?bool
    {
        return $tawaranTopik->delete();
    }

    /**
     * Mengembalikan data topik yang sudah dihapus.
     */
    public function restoreTopic(int $topicId): bool
    {
        $topic = $this->dosen->topik()->onlyTrashed()->findOrFail($topicId);
        return $topic->restore();
    }

    /**
     * Menghapus data topik secara permanen.
     */
    public function forceDeleteTopic(int $topicId): ?bool
    {
        $topic = $this->dosen->topik()->onlyTrashed()->findOrFail($topicId);
        return $topic->forceDelete();
    }

    /**
     * PENAMBAHAN: Method untuk menghapus semua topik di trash milik dosen.
     */
    public function forceDeleteAllTopics(): void
    {
        $this->dosen->topik()->onlyTrashed()->forceDelete();
    }
}
