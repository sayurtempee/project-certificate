@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('dashboard-content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 text-white rounded-xl p-6 mb-6 shadow-md">
        <h1 class="text-3xl font-bold mb-2">{{ $user->name }} Selamat Datang di Dashboard</h1>
        <p class="text-lg">
            Hai, <span class="font-semibold">{{ $user->name }}</span>! Selamat mengelola sertifikat Tahfidz SDIA 13
            Rawamangun.
        </p>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Jumlah Murid -->
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Jumlah Murid</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $studentsCount ?? '-' }}</p>
            </div>
            <i class="bi bi-mortarboard-fill text-4xl text-blue-600"></i>
        </div>

        <!-- Sertifikat Terbit -->
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Sertifikat Terbit</h2>
                <p class="text-2xl font-bold text-green-600">{{ $certificatesCount ?? '-' }}</p>
            </div>
            <i class="bi bi-award text-4xl text-green-600"></i>
        </div>

        @if (Auth::check() && Auth::user()->role === 'teacher')
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">Aksi Cepat</h2>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('student.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center">
                        <i class="bi bi-plus-circle-dotted mr-2 text-lg"></i> Tambah Sertifikat
                    </a>
                    <a href="{{ route('certificates.index') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center">
                        <i class="bi bi-award mr-2 text-lg"></i> Download Sertifikat
                    </a>
                </div>
            </div>
        @endif

        <!-- Guru Terdaftar -->
        @if (Auth::check() && Auth::user()->role === 'admin')
            <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Guru Terdaftar</h2>
                    <p class="text-2xl font-bold text-yellow-600">{{ $teachersCount ?? 0 }}</p>
                </div>
                <i class="bi bi-people-fill text-4xl text-yellow-600"></i>
            </div>
        @endif
    </div>

    <!-- Status Guru (Terpisah dari Card Utama) -->
    @if (Auth::check() && Auth::user()->role === 'admin')
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                <i class="bi bi-activity text-green-500 mr-2"></i> Status Aktivitas Guru
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Guru Online -->
                <div class="border border-green-200 rounded-lg p-4">
                    <h3 class="text-green-700 font-semibold mb-3 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Guru Online
                    </h3>
                    <ul class="space-y-2 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-green-200 pr-2">
                        @forelse ($teachers->where('is_online', true) as $teacher)
                            <li
                                class="flex justify-between items-center text-sm text-gray-700 bg-green-50 px-3 py-2 rounded-lg">
                                <span>{{ $teacher->name }}</span>
                                <span class="text-green-600 font-medium">Online</span>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 italic">Tidak ada guru online saat ini.</p>
                        @endforelse
                    </ul>
                </div>

                <!-- Guru Offline -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-gray-700 font-semibold mb-3 flex items-center">
                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span> Guru Offline
                    </h3>
                    <ul class="space-y-2 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 pr-2">
                        @forelse ($teachers->where('is_online', false) as $teacher)
                            <li
                                class="flex justify-between items-center text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                <span>{{ $teacher->name }}</span>
                                <span class="text-xs text-gray-400 italic">
                                    {{ $teacher->last_seen ? $teacher->last_seen->diffForHumans() : 'Belum login' }}
                                </span>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 italic">Semua guru sedang online 🎉</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endsection
