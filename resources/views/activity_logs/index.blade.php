@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-4" x-data="{ showSearch: false }">
            <h1 class="text-2xl font-bold">Activity Logs</h1>

            <div class="flex gap-2 items-center">
                <!-- Search -->
                <div x-show="showSearch" class="flex items-center gap-2" x-transition>
                    <form method="GET" action="{{ route('activity.logs.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari teacher..."
                                class="w-64 pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                            Filter
                        </button>
                        @if (request('search'))
                            <a href="{{ route('activity.logs.index') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Toggle Search Button -->
                <button @click="showSearch = !showSearch"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Download PDF -->
                <a href="{{ route('activity.logs.pdf') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    <i class="bi bi-download"></i> Download PDF
                </a>

                <!-- Hapus Semua -->
                <form action="{{ route('activity.logs.clear') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus semua activity logs?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
                        <i class="bi bi-trash"></i> Hapus Semua
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Logs -->
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">User</th>
                    <th class="border p-2">Action</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="border p-2">{{ $log->user->name ?? 'System' }}</td>
                        <td class="border p-2">{{ $log->action }}</td>
                        <td class="border p-2">{{ $log->description }}</td>
                        <td class="border p-2">{{ $log->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-2">Belum ada riwayat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
