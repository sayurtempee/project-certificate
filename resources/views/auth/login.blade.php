@extends('layouts.auth')

@section('title', 'Login - Project Certificate')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Login</h1>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-100 p-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="text-left">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Password -->
            <div class="text-left">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl shadow-md
                   hover:bg-blue-700 hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                Login
            </button>

            {{-- Tombol Forgot Password --}}
            <a href="#" class="block text-center mt-3 text-sm text-blue-600 hover:underline">
                Forgot Password?
            </a>
        </form>
    </div>
@endsection
