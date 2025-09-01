@extends('layouts.auth')

@section('title', 'Welcome - Project Certificate')

@section('content')
    <!-- Left Section -->
    <div class="flex flex-col items-center md:items-start justify-center p-10">
        <img src="{{ asset('img/logo.avif') }}" alt="Logo Project Certificate" class="h-24 md:h-28 w-auto mb-6">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-4 leading-snug">
            Selamat Datang 👋
        </h1>
        <p class="text-gray-600 text-base">
            <span class="font-medium text-blue-600">Project Certificate Management</span><br>
            Silakan login untuk melanjutkan
        </p>

        <p class="text-xs text-gray-400 mt-10">
            © {{ date('Y') }} Project Certificate. All rights reserved.
        </p>
    </div>

    <!-- Right Section -->
    <div class="flex items-center justify-center p-10 border-t md:border-t-0 md:border-l border-gray-200">
        <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center gap-2 py-3 px-8 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-base font-semibold rounded-lg shadow-md
                  hover:from-blue-700 hover:to-blue-600 hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
            <i data-feather="log-in" class="w-5 h-5"></i>
            <span>Login</span>
        </a>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
