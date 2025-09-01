@extends('layouts.auth')

@section('title', 'Forgot Password - Project Certificate')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 max-w-md w-full text-center">

        {{-- Judul --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Forgot Password</h1>
        <p class="text-sm text-gray-500 mb-6">
            Masukkan email kamu dan kami akan kirim link untuk reset password.
        </p>

        {{-- Notifikasi sukses --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4 text-left">
            @csrf

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <button type="submit"
                class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl shadow-md
                hover:bg-blue-700 hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                Kirim Link Reset
            </button>
        </form>

        {{-- Link kembali ke login --}}
        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                ← Kembali ke Login
            </a>
        </div>
    </div>
@endsection
