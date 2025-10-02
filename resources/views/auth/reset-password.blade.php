@extends('layouts.auth')

@section('title', 'Reset Password - Project Certificate')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Reset Password</h1>
        <p class="text-sm text-gray-500 mb-6">
            Masukkan password baru untuk akun kamu.
        </p>

        {{-- Pesan sukses --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Pesan error umum --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4 text-left">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" readonly
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed focus:outline-none">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Baru -->
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                        onclick="togglePassword('password', 'togglePasswordIcon1')">
                        <i id="togglePasswordIcon1" class="bi bi-eye-fill text-gray-600"></i>
                    </span>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="relative mt-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                    Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                        onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')">
                        <i id="togglePasswordIcon2" class="bi bi-eye-fill text-gray-600"></i>
                    </span>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl shadow-md
                   hover:bg-blue-700 hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                Reset Password
            </button>
        </form>

        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                ← Kembali ke Login
            </a>
        </div>
    </div>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-fill");
                icon.classList.add("bi-eye-slash-fill");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash-fill");
                icon.classList.add("bi-eye-fill");
            }
        }
    </script>
@endsection
