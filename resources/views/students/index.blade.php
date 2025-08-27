@extends('layouts.dashboard')

@section('title', 'Daftar Siswa')

@section('dashboard-content')
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white w-full rounded-2xl shadow-xl p-6 mb-6">
        <h1 class="text-3xl font-bold">Selamat Datang <span class="text-yellow-300">{{ Auth::user()->name }}</span></h1>
        <p class="mt-2 text-sm opacity-90">
            Daftar Siswa
        </p>
        <p class="mt-1 text-xs text-yellow-200">
            Note: A+ : 96-100, A : 90-95, B+ : 86-89, B : 80-85, C : 74,5-79, D = Remedial
        </p>
    </div>

    <!-- Search + Upload -->
    <div class="w-full flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-3">
        <!-- Search form -->
        <form action="{{ route('student.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa..."
                class="px-3 py-2 rounded-xl border w-48 md:w-auto focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <select name="juz" class="px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">Pilih Juz</option>
                @for ($i = 30; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('juz') == $i ? 'selected' : '' }}>Juz {{ $i }}
                    </option>
                @endfor
            </select>
            <button type="submit"
                class="bg-blue-600 px-4 py-2 rounded-xl text-white hover:bg-blue-700 transition font-semibold">
                🔍 Cari
            </button>
        </form>

        <!-- Upload form -->
        <form action="{{ route('student.import') }}" method="POST" enctype="multipart/form-data" class="inline-block">
            @csrf
            <input type="file" name="file" accept=".csv" class="hidden" id="csvFileInput" required>
            <label for="csvFileInput"
                class="bg-green-500 px-4 py-2 rounded-xl text-white font-semibold shadow hover:bg-green-600 transition cursor-pointer">
                Upload CSV
            </label>
            <button type="submit" class="hidden" id="csvSubmitBtn"></button>
        </form>
    </div>

    <!-- Table -->
    <div class="w-full bg-white rounded-2xl shadow-lg overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nama Siswa</th>
                    <th class="py-3 px-4">No Induk</th>
                    <th class="py-3 px-4">Juz</th>
                    <th class="py-3 px-4">Penyimak</th>
                    <th class="py-3 px-4">Kelancaran</th>
                    <th class="py-3 px-4">Fasohah</th>
                    <th class="py-3 px-4">Tajwid</th>
                    <th class="py-3 px-4">Total Kesalahan</th>
                    <th class="py-3 px-4">Nilai</th>
                    <th class="py-3 px-4">Predikat</th>
                    @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
                        <th class="py-3 px-4">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $currentJuz = null; @endphp
                @foreach ($students as $index => $student)
                    {{-- Header per Juz --}}
                    @if ($currentJuz != $student->juz)
                        <tr class="bg-blue-100">
                            <td colspan="12" class="py-2 px-4 font-bold">Juz {{ $student->juz }}</td>
                        </tr>
                        @php $currentJuz = $student->juz; @endphp
                    @endif

                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-3 px-4 font-semibold">{{ $students->firstItem() + $index }}.</td>
                        <td class="py-3 px-4">{{ $student->nama }}</td>
                        <td class="py-3 px-4">{{ $student->no_induk }}</td>
                        <td class="py-3 px-4">Juz {{ $student->juz }}</td>
                        <td class="py-3 px-4">{{ $student->penyimak }}</td>

                        @if (Auth::user()->role == 'teacher')
                            <form action="{{ route('student.updateInline', $student->id) }}" method="POST"
                                class="contents">
                                @csrf
                                @method('PUT')
                                <td class="py-3 px-4"><input type="number" name="kelancaran"
                                        value="{{ $student->kelancaran }}"
                                        class="w-16 border rounded px-1 text-sm text-center"></td>
                                <td class="py-3 px-4"><input type="number" name="fasohah" value="{{ $student->fasohah }}"
                                        class="w-16 border rounded px-1 text-sm text-center"></td>
                                <td class="py-3 px-4"><input type="number" name="tajwid" value="{{ $student->tajwid }}"
                                        class="w-16 border rounded px-1 text-sm text-center"></td>
                                <td class="py-3 px-4">{{ $student->total_kesalahan }}</td>
                                <td class="py-3 px-4">{{ $student->nilai }}</td>
                                <td
                                    class="py-3 px-4 font-bold {{ $student->nilai >= 96 ? 'text-green-600' : ($student->nilai >= 90 ? 'text-blue-600' : ($student->nilai >= 86 ? 'text-indigo-600' : ($student->nilai >= 80 ? 'text-yellow-600' : ($student->nilai >= 74.5 ? 'text-orange-600' : 'text-red-600')))) }}">
                                    {{ $student->predikat }}
                                </td>
                                <td class="py-3 px-4 flex space-x-2 items-center">
                                    <button type="submit" class="text-green-600 hover:text-green-800"><i
                                            class="bi bi-floppy-fill"></i></button>
                                    <a href="{{ route('student.edit', $student->id) }}"
                                        class="text-blue-600 hover:text-blue-800"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="{{ route('student.pdf', $student->id) }}"
                                        class="text-red-600 hover:text-red-800"><i
                                            class="bi bi-file-earmark-pdf-fill"></i></a>
                                </td>
                            </form>
                        @else
                            {{-- Untuk admin / non-teacher --}}
                            <td class="py-3 px-4">{{ $student->kelancaran }}</td>
                            <td class="py-3 px-4">{{ $student->fasohah }}</td>
                            <td class="py-3 px-4">{{ $student->tajwid }}</td>
                            <td class="py-3 px-4">{{ $student->total_kesalahan }}</td>
                            <td class="py-3 px-4">{{ $student->nilai }}</td>
                            <td
                                class="py-3 px-4 font-bold {{ $student->nilai >= 96 ? 'text-green-600' : ($student->nilai >= 90 ? 'text-blue-600' : ($student->nilai >= 86 ? 'text-indigo-600' : ($student->nilai >= 80 ? 'text-yellow-600' : ($student->nilai >= 74.5 ? 'text-orange-600' : 'text-red-600')))) }}">
                                {{ $student->predikat }}
                            </td>
                            @if (Auth::user()->role == 'admin')
                                <td class="py-3 px-4">
                                    <a href="#"
                                        onclick="event.preventDefault(); if(confirm('Yakin ingin hapus?')) { document.getElementById('delete-student-{{ $student->id }}').submit(); }"
                                        class="text-red-600 hover:text-red-800"><i class="bi bi-trash-fill"></i></a>
                                    <form id="delete-student-{{ $student->id }}"
                                        action="{{ route('student.destroy', $student->id) }}" method="POST"
                                        style="display:none">@csrf @method('DELETE')</form>
                                </td>
                            @endif
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $students->links() }}
    </div>

    <script>
        document.getElementById('csvFileInput').addEventListener('change', function() {
            document.getElementById('csvSubmitBtn').click();
        });
    </script>
@endsection
