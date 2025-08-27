@extends('layouts.auth')

@section('title', 'Welcome - Project Certificate')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 max-w-md w-full text-center">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-4">
            Selamat Datang 👋
        </h1>
        <p class="text-gray-600 mb-8">
            Project Certificate Management <br>
            Silakan login untuk melanjutkan
        </p>

        <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center gap-2 w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl shadow-md
                  hover:bg-blue-700 hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
            <!-- Feather icon (login/arrow-right) -->
            <i data-feather="log-in"></i>
            Login
        </a>
    </div>
@endsection
