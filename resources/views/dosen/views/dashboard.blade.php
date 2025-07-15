@extends('layouts.template.main')

{{-- ✅ PERBAIKAN: Judul dibuat statis dan jelas. Logika prioritas dihapus dari view. --}}
@section('title', 'Dashboard Dosen')

@section('content')

    <!-- Header Main -->
    @include('layouts.components.content-dosen.header')
    <!-- end header main -->

    <!-- Main Cards (Card 1 - 4) -->
    <div class="row">
        {{-- Komponen-komponen ini akan otomatis menerima variabel seperti $card1, $card2, dll. dari Controller --}}
        @include('layouts.components.content-dosen.card-1', ['data' => $card1])
        @include('layouts.components.content-dosen.card-2', ['data' => $card2])
        @include('layouts.components.content-dosen.card-3', ['data' => $card3])
        @include('layouts.components.content-dosen.card-4', ['data' => $card4])
    </div>
    <!-- end main cards -->

    {{-- ✅ PERBAIKAN: Kondisi sekarang menggunakan variabel boolean yang bersih dari controller --}}

    <!-- RIWAYAT PENGAJUAN TA HANYA UNTUK PIMPINAN JURUSAN -->

    <div class="row">
        <!-- JADWAL BIMBINGAN KHUSUS PEMBIMBING -->
        @if ($isPembimbing)
            @include('layouts.components.content-dosen.jadwal-bimbingan', [
                'jadwalBimbingan' => $jadwalBimbingan,
            ])
        @endif

        <!-- JADWAL SIDANG KHUSUS PENGUJI -->
        @if ($isPenguji)
            @include('layouts.components.content-dosen.jadwal-sidang', ['jadwalSidang' => $jadwalSidang])
        @endif

        @if ($isPimpinan)
            <div class="row">
                @include('layouts.components.content-dosen.riwayatpengajuanta', [
                    'riwayatTA' => $riwayatTA,
                ])
            </div>
        @endif
        <div class="col-md-6">
            @include('layouts.components.content-dosen.pengumuman', ['pengumumans' => $pengumumans])
        </div>
        <div class="col-md-6">
            @include('layouts.components.content-dosen.topik', ['tawaranTopik' => $tawaranTopik])
        </div>
    </div>

    {{-- <!-- Topik: wajib tampil untuk semua dosen (tidak perlu @if) --> --}}
    <div class="row">
    </div>

    <!-- Pengumuman: wajib tampil -->
    <div class="row">
    </div>

@endsection
