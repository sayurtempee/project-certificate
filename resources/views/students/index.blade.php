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
    <div class="container mx-auto px-5 py-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-1">
            <i class="bi bi-book"></i> Daftar Murid Juz {{ $juz ?? 'Semua' }}
        </h3>

        {{-- Form filter --}}
        <form method="GET" action="{{ route('student.index') }}"
            class="bg-white rounded-xl shadow-md p-4 mt-[-4px] mb-4 space-y-4 md:space-y-0 md:flex md:items-center md:gap-4">

            {{-- Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="🔍 Cari nama murid...">
            </div>

            {{-- Filter Juz --}}
            <div>
                <select name="juz"
                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onchange="this.form.submit()">
                    <option value="">📖 Semua Juz</option>
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" {{ $juz == $i ? 'selected' : '' }}>
                            Juz {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            @if (auth()->user()->role === 'admin')
                {{-- Filter Tahun Ajaran --}}
                <div>
                    <select name="tahun"
                        class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
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

                {{-- Filter Penyimak (khusus admin) --}}
                <div>
                    <select name="penyimak"
                        class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
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

            {{-- Tombol --}}
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 text-sm rounded-lg shadow transition">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>

        {{-- Upload CSV dan create murid satuan hanya untuk teacher --}}
        @if (Auth::user()->role == 'teacher')
            <div class="mb-6 flex items-center gap-3">
                <!-- Upload CSV -->
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-3">
                    @csrf
                    <label for="csvFileInput"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 text-sm rounded-lg shadow cursor-pointer transition flex items-center gap-2">
                        <i class="bi bi-upload"></i> Upload Data Siswa (CSV)
                    </label>
                    <input type="file" name="file" accept=".csv" class="hidden" id="csvFileInput" required>
                    <button type="submit" class="hidden" id="csvSubmitBtn"></button>
                </form>

                <!-- Export Sample CSV -->
                <a href="{{ route('students.exportSampleCsv') }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-1.5 text-sm rounded-lg shadow transition flex items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-down"></i> Download Sample (CSV)
                </a>

                <!-- Tambah Siswa -->
                <a href="{{ route('student.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 text-sm rounded-lg shadow transition flex items-center gap-2">
                    <i class="bi bi-plus-circle-dotted"></i> Tambah Data Satuan Murid
                </a>
            </div>
        @endif

        @if (Auth::user()->role == 'admin')
            <div class="mb-4">
                <select onchange="if(this.value) window.location.href=this.value"
                    class="w-full rounded-xl border border-gray-300 shadow-sm px-4 py-2 text-sm font-medium
                text-gray-700 bg-white hover:bg-gray-50 transition duration-150">

                    <option selected disabled>📦 Rekap Tahunan</option>
                    @forelse ($tahunList as $tahun)
                        <option value="{{ route('students.rekap', $tahun) }}">
                            📄 Download Rekap {{ $tahun }}
                        </option>
                    @empty
                        <option disabled>Tidak ada rekapan tahunan</option>
                    @endforelse
                </select>
            </div>
        @endif

        {{-- Table siswa --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-left">
                    <thead class="bg-slate-800 text-white text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Nama Murid</th>
                            <th class="px-6 py-3">No Induk</th>
                            <th class="px-6 py-3">Juz</th>
                            <th class="px-6 py-3">Penyimak</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($students as $s)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3">{{ $s->nama }}</td>
                                <td class="px-6 py-3">{{ $s->no_induk }}</td>
                                <td class="px-6 py-3">{{ $s->juz ?? $s->juzData }}</td>
                                <td class="px-6 py-3">
                                    {{ $s->penyimak ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if (Auth::user()->role == 'teacher')
                                        <a href="{{ route('student.show', $s->id) }}"
                                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm shadow transition">
                                            <i class="bi bi-ticket-detailed"></i> Detail Nilai
                                        </a>
                                        <a href="{{ route('student.pdf', $s->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                            <i class="bi bi-file-earmark-pdf mr-2"></i>
                                            Download PDF
                                        </a>
                                    @elseif (Auth::user()->role == 'admin')
                                        <form action="{{ route('student.destroy', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus murid ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" disabled
                                                class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm shadow transition cursor-not-allowed opacity-60">
                                                <i class="bi bi-trash"></i> Hapus Murid
                                            </button>
                                        </form>
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

        document.querySelector('form[action="{{ route('student.index') }}"]').addEventListener('submit', function(e) {
            // hapus field kosong sebelum submit
            this.querySelectorAll('input, select').forEach(el => {
                if (!el.value) el.removeAttribute('name');
            });
        });
    </script>
@endsection
