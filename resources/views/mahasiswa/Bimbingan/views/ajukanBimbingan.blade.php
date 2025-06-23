@extends('layouts.template.main')
@section('title', 'Jadwal Sidang')
@section('content')
    @include('mahasiswa.Bimbingan.crud-bimbingan.ajukan')
@endsection

@push('scripts')
    <script>
        // Tampilkan SweetAlert jika ada pesan sukses atau error dari session
        @if (session('success'))
            swal({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                button: {
                    text: "OK",
                    className: "btn btn-success"
                }
            });
        @elseif (session('error'))
            swal({
                title: "Gagal!",git
                text: "{{ session('error') }}",
                icon: "error",
                button: {
                    text: "OK",
                    className: "btn btn-danger"
                }
            });
        @endif
    </script>
@endpush
