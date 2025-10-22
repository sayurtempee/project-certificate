@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Tombol kembali -->
        <a href="{{ route('student.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition mb-6">
            <i class="bi bi-arrow-left-circle me-2"></i>
            Kembali ke Student Index
        </a>

        <!-- Kontainer laporan -->
        <div class="bg-white shadow-lg rounded-2xl p-6 sm:p-8 border border-gray-300">

            <!-- Header -->
            <div class="text-center mb-6 border-b border-gray-400 pb-4">
                <h1 class="text-lg sm:text-xl font-bold uppercase text-slate-800">Laporan Nilai Murid</h1>
                <p class="text-gray-600 text-sm sm:text-base">Detail Nilai Tilawah</p>
            </div>

            <!-- Info Siswa -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 text-sm sm:text-base">
                <div><strong>Nama:</strong> {{ $student->nama }}</div>
                <div><strong>No Induk:</strong> {{ $student->no_induk }}</div>
                <div><strong>Penyimak:</strong> {{ $student->penyimak ?? '-' }}</div>
                <div><strong>Juz:</strong> {{ $student->juz }}</div>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('student.updateInline', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Table Wrapper -->
                <div class="overflow-x-auto rounded-lg border border-gray-300 shadow-sm">
                    <table class="min-w-full text-xs sm:text-sm text-left">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="border px-2 py-2">Surat ke</th>
                                <th class="border px-2 py-2">Nama Surat</th>
                                <th class="border px-2 py-2">Jumlah Ayat</th>
                                <th class="border px-2 py-2 text-center">Kelancaran</th>
                                <th class="border px-2 py-2 text-center">Fasohah</th>
                                <th class="border px-2 py-2 text-center">Tajwid</th>
                                <th class="border px-2 py-2 text-center">Kesalahan</th>
                                <th class="border px-2 py-2 text-center">Nilai</th>
                                <th class="border px-2 py-2 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($student->surats as $index => $s)
                                <tr class="text-center hover:bg-gray-50 transition">
                                    <td class="border px-2 py-2">
                                        <input type="hidden" name="surat[{{ $index }}][id]"
                                            value="{{ $s->id }}">
                                        {{ $s->surat_ke }}
                                    </td>
                                    <td class="border px-2 py-2 whitespace-nowrap">{{ $s->nama_surat }}</td>
                                    <td class="border px-2 py-2">{{ (string) $s->ayat }}</td>
                                    <td class="border px-2 py-2">
                                        <input type="number" name="surat[{{ $index }}][kelancaran]"
                                            value="{{ old('surat.' . $index . '.kelancaran', default: $s->kelancaran) }}"
                                            class="w-14 sm:w-16 border rounded text-center text-xs sm:text-sm focus:ring-1 focus:ring-blue-500"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="border px-2 py-2">
                                        <input type="number" name="surat[{{ $index }}][fasohah]"
                                            value="{{ old('surat.' . $index . '.fasohah', $s->fasohah) }}"
                                            class="w-14 sm:w-16 border rounded text-center text-xs sm:text-sm focus:ring-1 focus:ring-blue-500"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="border px-2 py-2">
                                        <input type="number" name="surat[{{ $index }}][tajwid]"
                                            value="{{ old('surat.' . $index . '.tajwid', $s->tajwid) }}"
                                            class="w-14 sm:w-16 border rounded text-center text-xs sm:text-sm focus:ring-1 focus:ring-blue-500"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="border px-2 py-2">
                                        <input type="number" value="{{ $s->total_kesalahan }}"
                                            class="w-14 sm:w-16 border rounded text-center text-xs sm:text-sm bg-gray-100"
                                            readonly>
                                    </td>
                                    <td class="border px-2 py-2">
                                        <input type="number" value="{{ $s->nilai }}"
                                            class="w-14 sm:w-16 border rounded text-center text-xs sm:text-sm bg-gray-100"
                                            readonly>
                                    </td>
                                    <td class="border px-2 py-2 text-sm">{{ $s->predikat }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Rekap -->
                <div class="mt-4 text-sm border border-gray-400 p-4 bg-gray-50 rounded-lg">
                    @php
                        $totalNilai = $surats->sum('nilai');
                        $rataRata = $surats->count() > 0 ? $totalNilai / $surats->count() : 0;
                        $predikatFinal = 'D';
                        if ($rataRata >= 96) {
                            $predikatFinal = 'A+';
                        } elseif ($rataRata >= 90) {
                            $predikatFinal = 'A';
                        } elseif ($rataRata >= 86) {
                            $predikatFinal = 'B+';
                        } elseif ($rataRata >= 80) {
                            $predikatFinal = 'B';
                        } elseif ($rataRata >= 74.5) {
                            $predikatFinal = 'C';
                        }
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <p><strong>Total Nilai:</strong> {{ number_format($totalNilai, 1) }}</p>
                        <p><strong>Rata-rata:</strong> {{ number_format($rataRata, 1) }}</p>
                        <p><strong>Predikat Akhir:</strong> {{ $predikatFinal }}</p>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm sm:text-base font-semibold shadow transition">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
