@extends('layouts.dashboard')

<head>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<style>
    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(8px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }
</style>

@section('dashboard-content')
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-6 flex items-center gap-1">
            <i class="bi bi-book"></i> Daftar Murid Juz {{ $juz ?? 'Semua' }}
        </h3>

        {{-- Form filter --}}
        <form method="GET" action="{{ route('student.index') }}"
            class="bg-white rounded-xl shadow-md p-4 space-y-3 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:gap-3">

            {{-- Search --}}
            <div class="w-full sm:flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="🔍 Cari nama murid...">
            </div>

            {{-- Filter Juz --}}
            <div class="w-full sm:w-auto">
                <select name="juz"
                    class="w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onchange="this.form.submit()">
                    <option value="">📖 Semua Juz</option>
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" {{ $juz == $i ? 'selected' : '' }}>Juz {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            @if (auth()->user()->role === 'admin')
                {{-- Filter Tahun Ajaran --}}
                <div class="w-full sm:w-auto">
                    <select name="tahun"
                        class="w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        onchange="this.form.submit()">
                        @if ($tahunList->isNotEmpty())
                            <option value="">📅 Semua Tahun</option>
                            @foreach ($tahunList as $t)
                                <option value="{{ $t }}" {{ isset($tahun) && $tahun == $t ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endforeach
                        @else
                            <option disabled selected>❌ Tidak ada rekapan</option>
                        @endif
                    </select>
                </div>

                {{-- Filter Penyimak --}}
                <div class="w-full sm:w-auto">
                    <select name="penyimak"
                        class="w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        onchange="this.form.submit()">
                        @if ($penyimakList->isNotEmpty())
                            <option value="">👨‍🏫 Semua Penyimak</option>
                            @foreach ($penyimakList as $p)
                                <option value="{{ $p }}"
                                    {{ isset($penyimak) && $penyimak == $p ? 'selected' : '' }}>
                                    {{ $p }}
                                </option>
                            @endforeach
                        @else
                            <option disabled selected>❌ Tidak ada penyimak</option>
                        @endif
                    </select>
                </div>
            @endif

            {{-- Tombol Search --}}
            <div class="w-full sm:w-auto">
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-lg shadow transition flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>

        {{-- Tombol Upload & Tambah (Teacher Only) --}}
        @if (Auth::user()->role == 'teacher')
            <div class="mt-6 flex flex-col sm:flex-row sm:flex-wrap gap-3">
                {{-- Upload CSV --}}
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-2 w-full sm:w-auto">
                    @csrf
                    <label for="csvFileInput"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded-lg shadow cursor-pointer transition flex items-center gap-2 w-full sm:w-auto justify-center">
                        <i class="bi bi-upload"></i> Upload Data (CSV)
                    </label>
                    <input type="file" name="file" accept=".csv" class="hidden" id="csvFileInput" required>
                    <button type="submit" class="hidden" id="csvSubmitBtn"></button>
                </form>

                {{-- Export Sample --}}
                <a href="{{ route('students.exportSampleCsv') }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 text-sm rounded-lg shadow transition flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="bi bi-file-earmark-arrow-down"></i> Sample CSV
                </a>

                {{-- Tambah Siswa --}}
                <a href="{{ route('student.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-lg shadow transition flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="bi bi-plus-circle-dotted"></i> Tambah Murid
                </a>

                {{-- Download Sertifikat --}}
                <a href="{{ route('certificates.index') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 text-sm rounded-lg shadow transition flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="bi bi-award"></i> Sertifikat
                </a>
            </div>
        @endif

        {{-- Table siswa --}}
        <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-slate-800 text-white text-xs sm:text-sm uppercase">
                        <tr>
                            <th class="px-4 sm:px-6 py-3">Nama Murid</th>
                            <th class="px-4 sm:px-6 py-3">No Induk</th>
                            <th class="px-4 sm:px-6 py-3">Juz</th>
                            <th class="px-4 sm:px-6 py-3">Penyimak</th>
                            <th class="px-4 sm:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($students as $s)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 sm:px-6 py-3">{{ $s->nama }}</td>
                                <td class="px-4 sm:px-6 py-3">{{ $s->no_induk }}</td>
                                <td class="px-4 sm:px-6 py-3">{{ $s->juz ?? $s->juzData }}</td>
                                <td class="px-4 sm:px-6 py-3">{{ $s->penyimak ?? '-' }}</td>
                                <td class="px-4 sm:px-6 py-3 text-center flex flex-wrap justify-center gap-2">
                                    @if (Auth::user()->role == 'teacher')
                                        <a href="{{ route('student.show', $s->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs sm:text-sm shadow transition">
                                            <i class="bi bi-ticket-detailed"></i> Detail
                                        </a>
                                        <a href="{{ route('student.pdf', $s->id) }}"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs sm:text-sm shadow transition">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    🚫 Belum ada data murid untuk Juz ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('csvFileInput');
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                this.form.submit();
            }
        });

        document.querySelector('form[action="{{ route('student.index') }}"]').addEventListener('submit', function() {
            this.querySelectorAll('input, select').forEach(el => {
                if (!el.value) el.removeAttribute('name');
            });
        });
    </script>
@endsection
