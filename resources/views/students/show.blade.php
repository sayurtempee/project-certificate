@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="max-w-7xl mx-auto px-6 py-10">
        <a href="{{ route('student.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition">
            <i class="bi bi-arrow-left-circle me-2"></i>
            Kembali ke Student Index
        </a>

        <div class="bg-gradient-to-br from-white to-blue-50 shadow-lg rounded-2xl p-8 border border-slate-200">

            <!-- Judul -->
            <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-3">
                <h3 class="text-3xl font-extrabold text-slate-800 flex items-center gap-2">
                    📖 Detail & Edit Nilai
                    <span class="text-blue-600">- {{ $student->nama }}</span>
                </h3>
            </div>

            <!-- Info siswa -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 text-sm">
                <div class="p-3 rounded-lg bg-slate-100">
                    <strong>No Induk:</strong> {{ $student->no_induk }}
                </div>
                <div class="p-3 rounded-lg bg-slate-100">
                    <strong>Penyimak:</strong> {{ $student->penyimak ?? ($student->penyimak = auth()->user()->name) }}
                </div>
                <div class="p-3 rounded-lg bg-slate-100">
                    <strong>Juz:</strong> {{ $student->juz }}
                </div>
            </div>

            <!-- Alert -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300 text-sm font-medium">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('student.updateInline', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="juz" value="{{ $student->juz ?? '' }}">

                <!-- Tabel -->
                <div class="overflow-x-auto rounded-xl border border-gray-300 shadow-md">
                    <table class="table-auto w-full text-sm">
                        <thead class="bg-slate-800 text-white text-sm uppercase">
                            <tr>
                                <th class="px-4 py-3 text-center">Surat ke</th>
                                <th class="px-4 py-3 text-center">Nama Surat</th>
                                <th class="px-4 py-3 text-center">Jumlah Ayat</th>
                                <th class="px-4 py-3 text-center">Kelancaran</th>
                                <th class="px-4 py-3 text-center">Fasohah</th>
                                <th class="px-4 py-3 text-center">Tajwid</th>
                                <th class="px-4 py-3 text-center">Total Kesalahan</th>
                                <th class="px-4 py-3 text-center">Nilai</th>
                                <th class="px-4 py-3 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($surats as $index => $s)
                                <tr class="hover:bg-blue-50 transition text-center">
                                    <td class="px-4 py-3 font-semibold text-slate-700">
                                        <input type="hidden" name="surat[{{ $index }}][id]"
                                            value="{{ $s->id }}">
                                        <input type="hidden" name="surat[{{ $index }}][surat_ke]"
                                            value="{{ $s->surat_ke }}">
                                        {{ $s->surat_ke }}
                                    </td>
                                    <td class="px-4 py-3">{{ $s->nama_surat }}</td>
                                    <td class="px-4 py-3">{{ $s->ayat }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="surat[{{ $index }}][kelancaran]"
                                            value="{{ old('surat.' . $index . '.kelancaran', $s->kelancaran) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50 focus:ring-2
                                                   focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="surat[{{ $index }}][fasohah]"
                                            value="{{ old('surat.' . $index . '.fasohah', $s->fasohah) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50 focus:ring-2
                                                   focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="surat[{{ $index }}][tajwid]"
                                            value="{{ old('surat.' . $index . '.tajwid', $s->tajwid) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50 focus:ring-2
                                                   focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" class="w-24 bg-gray-100 border-gray-200 rounded-md"
                                            value="{{ $s->total_kesalahan }}" readonly>
                                        <input type="hidden" name="surat[{{ $index }}][total_kesalahan]"
                                            value="{{ $s->total_kesalahan }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" class="w-20 bg-gray-100 border-gray-200 rounded-md"
                                            value="{{ $s->nilai }}" readonly>
                                        <input type="hidden" name="surat[{{ $index }}][nilai]"
                                            value="{{ $s->nilai }}">
                                    </td>

                                    @php
                                        switch ($s->predikat) {
                                            case 'A+':
                                                $badgeClass = 'bg-green-600 text-white';
                                                break;
                                            case 'A':
                                                $badgeClass = 'bg-green-500 text-white';
                                                break;
                                            case 'B+':
                                                $badgeClass = 'bg-blue-500 text-white';
                                                break;
                                            case 'B':
                                                $badgeClass = 'bg-blue-400 text-white';
                                                break;
                                            case 'C':
                                                $badgeClass = 'bg-yellow-400 text-black';
                                                break;
                                            case 'D':
                                                $badgeClass = 'bg-red-500 text-white';
                                                break;
                                            default:
                                                $badgeClass = 'bg-gray-300 text-gray-700';
                                        }
                                    @endphp
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="surat[{{ $index }}][predikat]"
                                            value="{{ $s->predikat }}">
                                        <span
                                            class="predikat-badge px-3 py-1 rounded-full text-sm font-bold shadow {{ $badgeClass }}">
                                            {{ $s->predikat }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg shadow-lg font-semibold tracking-wide transition">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getPredikatBadge(predikat) {
            const colors = {
                "A+": "bg-green-600 text-white",
                "A": "bg-green-500 text-white",
                "B+": "bg-blue-500 text-white",
                "B": "bg-blue-400 text-white",
                "C": "bg-yellow-400 text-black",
                "D": "bg-red-500 text-white",
            };
            return `<span class="px-3 py-1 rounded-full text-sm font-bold shadow ${colors[predikat] || 'bg-gray-300 text-gray-700'}">${predikat}</span>`;
        }

        function hitungNilai(index) {
            const row = document.querySelectorAll('#suratTable tbody tr')[index];

            const kelancaran = parseInt(row.querySelector(`input[name="surat[${index}][kelancaran]"]`).value) || 0;
            const fasohah = parseInt(row.querySelector(`input[name="surat[${index}][fasohah]"]`).value) || 0;
            const tajwid = parseInt(row.querySelector(`input[name="surat[${index}][tajwid]"]`).value) || 0;

            const totalKesalahan = kelancaran + fasohah + tajwid;
            row.querySelector(`input[name="surat[${index}][total_kesalahan]"]`).value = totalKesalahan;

            const nilai = Math.max(0, 100 - (totalKesalahan * 1.7));
            row.querySelector(`input[name="surat[${index}][nilai]"]`).value = nilai.toFixed(2);

            let predikat = "D";
            if (nilai >= 96) predikat = "A+";
            else if (nilai >= 90) predikat = "A";
            else if (nilai >= 86) predikat = "B+";
            else if (nilai >= 80) predikat = "B";
            else if (nilai >= 74.5) predikat = "C";

            row.querySelector(`input[name="surat[${index}][predikat]"]`).value = predikat;
            row.querySelector(`.predikat-badge`).outerHTML = getPredikatBadge(predikat);
        }
    </script>
@endsection
