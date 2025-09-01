@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            📚 Daftar Siswa Juz {{ $juz ?? 'Semua' }}
        </h3>

        {{-- Form filter --}}
        <form method="GET" action="{{ route('student.index') }}"
            class="bg-white rounded-xl shadow-md p-4 mb-6 space-y-4 md:space-y-0 md:flex md:items-center md:gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="🔍 Cari nama siswa...">
            </div>
            <div>
                <select name="juz"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onchange="this.form.submit()">
                    <option value="">📖 Semua Juz</option>
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" {{ $juz == $i ? 'selected' : '' }}>
                            Juz {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition">
                    🔎 Filter
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
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow cursor-pointer transition flex items-center gap-2">
                        ⬆️ Upload CSV
                    </label>
                    <input type="file" name="file" accept=".csv" class="hidden" id="csvFileInput" required>
                    <button type="submit" class="hidden" id="csvSubmitBtn"></button>
                </form>

                <!-- Tambah Siswa -->
                <a href="{{ route('student.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                    ➕ Tambah Siswa
                </a>
            </div>
        @endif

        {{-- Table siswa --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-left">
                    <thead class="bg-slate-800 text-white text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Nama Siswa</th>
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
                                            📑 Detail Nilai
                                        </a>
                                        <a href="{{ route('student.pdf', $s->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                            <i class="bi bi-file-earmark-pdf mr-2"></i>
                                            Download PDF
                                        </a>
                                    @elseif (Auth::user()->role == 'admin')
                                        <form action="{{ route('student.destroy', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus siswa ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm shadow transition">
                                                🗑️ Hapus Siswa
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    🚫 Belum ada data siswa untuk Juz ini
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
    </script>
@endsection
