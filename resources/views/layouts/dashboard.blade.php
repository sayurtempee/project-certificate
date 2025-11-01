@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')
        @include('layouts.partials.swal')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="flex justify-between items-center bg-white shadow px-6 py-3">
                <h2 class="text-lg font-semibold text-blue-800">@yield('title', 'Dashboard')</h2>

                <div class="relative flex items-center space-x-3">
                    {{-- Nama User / Guest --}}
                    <span class="text-sm font-medium text-gray-700">
                        {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                    </span>

                    {{-- Profile + Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="focus:outline-none">
                            @if (Auth::check() && Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile"
                                    class="w-10 h-10 rounded-full border-2 border-blue-700 object-cover">
                            @else
                                {{-- Inisial jika tidak ada foto --}}
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-700 text-white font-bold border-2 border-blue-700 uppercase">
                                    {{ Auth()->user()->getInitials() }}
                                </div>
                            @endif
                        </button>

                        @auth
                            <!-- Dropdown menu (hanya muncul kalau login) -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">

                                {{-- Kalau role teacher ada Edit Profile --}}
                                @if (Auth::check() && Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-100">
                                        Edit Admin Profile
                                    </a>
                                @elseif (Auth::check() && Auth::user()->role == 'teacher')
                                    <a href="{{ route('teacher.edit', Auth::user()->id) }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-blue-100">
                                        Edit Profile
                                    </a>
                                @endif

                                {{-- Logout untuk semua yang login --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto">
                @yield('dashboard-content')
                <div class="h-12"></div>

                <footer class="fixed bottom-0 left-0 w-full text-center text-gray-500 py-2">
                    <div class="max-w-screen-sm mx-auto">
                        Website certificate ini dibuat oleh
                        <a href="https://github.com/sayurtempee/project-certificate" class="ml-1 hover:text-black inline-flex items-center">
                            <i class="bi bi-github mr-1"></i> sayurtempee.
                        </a>
                        <div class="text-sm">Versi 1.0</div>
                    </div>
                </footer>
            </main>
        </div>
    </div>
@endsection
