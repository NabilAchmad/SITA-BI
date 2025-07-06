<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini
use Tests\TestCase;

class ValidasiTugasAkhirTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Kaprodi dapat menyetujui pengajuan tugas akhir.
     */
    public function kaprodi_can_approve_a_thesis_submission(): void
    {
        // =================================================================
        // PERBAIKAN: Membuat data secara manual untuk menghindari error factory
        // =================================================================

        // 1. Persiapan Data (Setup)
        $roleKaprodi = Role::create([
            'nama_role' => 'kaprodi-d3',
            'deskripsi' => 'Kaprodi D3'
        ]);

        // Buat user kaprodi secara manual
        $kaprodiUser = User::create([
            'name' => 'Kaprodi D3 Test',
            'email' => 'kaprodi.d3@test.com',
            'password' => Hash::make('password'), // Pastikan ada password
            // Tambahkan kolom lain yang mungkin wajib diisi di tabel users Anda
        ]);

        // Hubungkan user dengan role
        $kaprodiUser->roles()->attach($roleKaprodi->id);

        // Buat mahasiswa secara manual
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@test.com',
            'password' => Hash::make('password'),
        ]);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'nim' => '123456',
            'prodi' => 'D3',
            'angkatan' => "2023",
            // Tambahkan kolom lain yang mungkin wajib diisi di tabel mahasiswa Anda
        ]);

        // Buat tugas akhir secara manual
        $tugasAkhir = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Tugas Akhir Untuk Test',
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
            'disetujui_oleh' => null,
        ]);

        // 2. Aksi (Action)
        // Jalankan tes sebagai user kaprodi yang sudah kita buat
        $response = $this->actingAs($kaprodiUser)
            ->post(route('dosen.validasi-tugas-akhir.terima', $tugasAkhir));

        // 3. Pengecekan Hasil (Assertions)
        $response->assertRedirect();

        $this->assertDatabaseHas('tugas_akhir', [
            'id' => $tugasAkhir->id,
            'status' => 'disetujui',
            'disetujui_oleh' => $kaprodiUser->id,
        ]);
    }
}
