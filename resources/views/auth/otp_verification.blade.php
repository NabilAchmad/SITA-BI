@extends('layouts.template.homepage')

@section('content')
<div class="container mx-auto max-w-md mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-6">Verifikasi Kode OTP</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('auth.otp.verify.post') }}">
        @csrf
        <div class="mb-4">
            <label for="otp_code" class="block text-gray-700 font-medium mb-2">Kode OTP</label>
            <input type="text" name="otp_code" id="otp_code" maxlength="6" required autofocus
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-500"
                value="{{ old('otp_code') }}">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Verifikasi
        </button>
    </form>
</div>
@endsection
