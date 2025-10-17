@extends('layouts.auth')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-5xl w-full grid grid-cols-1 md:grid-cols-2">

        {{-- Bagian kiri: Form login --}}
        <div class="p-8 sm:p-10 flex flex-col justify-center">
            <div class="flex items-center space-x-4 mb-6">
                <img src="{{ asset('img/logo.avif') }}" alt="Logo 1" class="h-12">
            </div>

            <h1 class="text-2xl font-bold mb-2">Selamat Datang 👋</h1>
            <p class="text-gray-600 mb-6">
                Silakan login untuk mengakses
                <span class="text-blue-600 font-semibold">Project Certificate Management</span>.
            </p>

            <!-- Error Message -->
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-100 p-3 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            {{--  SweetAlert2  --}}
            @include('layouts.partials.swal')

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300">
                </div>
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 transition">

                    <!-- Icon toggle password -->
                    <span onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500 mt-6">
                        <i id="togglePasswordIcon" class="bi bi-eye-fill"></i>
                    </span>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md shadow-md">
                    Login
                </button>
            </form>

            <div class="mt-4 text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Lupa Password?
                </a>
            </div>

            <p class="mt-6 text-xs text-gray-500 text-center">
                © 2025 Project Certificate. All rights reserved.
            </p>
        </div>

        {{-- Bagian kanan: Gambar --}}
        <div class="hidden md:block">
            <img src="{{ asset('img/sdia13.png') }}" alt="Illustration" class="w-full h-full object-cover">
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("togglePasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye-fill");
                toggleIcon.classList.add("bi-eye-slash-fill");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye-slash-fill");
                toggleIcon.classList.add("bi-eye-fill");
            }
        }
    </script>
@endsection
