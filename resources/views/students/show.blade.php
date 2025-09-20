@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="max-w-5xl mx-auto px-6 py-10">

        <!-- Tombol kembali -->
        <a href="{{ route('student.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition mb-6">
            <i class="bi bi-arrow-left-circle me-2"></i>
            Kembali ke Student Index
        </a>

        <!-- Kontainer laporan -->
        <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-300">

            <!-- Header -->
            <div class="text-center mb-6 border-b border-gray-400 pb-4">
                <h1 class="text-xl font-bold uppercase text-slate-800">Laporan Nilai Santri</h1>
                <p class="text-gray-600">Detail Nilai Tilawah</p>
            </div>

            <!-- Info Siswa -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div><strong>Nama:</strong> {{ $student->nama }}</div>
                <div><strong>No Induk:</strong> {{ $student->no_induk }}</div>
                <div><strong>Penyimak:</strong> {{ $student->penyimak ?? '-' }}</div>
                <div><strong>Juz:</strong> {{ $student->juz }}</div>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('student.updateInline', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full border border-black text-xs">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="border px-2 py-1">Surat ke</th>
                                <th class="border px-2 py-1">Nama Surat</th>
                                <th class="border px-2 py-1">Jumlah Ayat</th>
                                <th class="border px-2 py-1">Kelancaran</th>
                                <th class="border px-2 py-1">Fasohah</th>
                                <th class="border px-2 py-1">Tajwid</th>
                                <th class="border px-2 py-1">Total Kesalahan</th>
                                <th class="border px-2 py-1">Nilai</th>
                                <th class="border px-2 py-1">Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surats as $index => $s)
                                <tr class="text-center">
                                    <td class="border px-2 py-1">
                                        <input type="hidden" name="surat[{{ $index }}][id]"
                                            value="{{ $s->id }}">
                                        {{ $s->surat_ke }}
                                    </td>
                                    <td class="border px-2 py-1">{{ $s->nama_surat }}</td>
                                    <td class="border px-2 py-1">{{ $s->ayat }}</td>
                                    <td class="border px-2 py-1">
                                        <input type="number" name="surat[{{ $index }}][kelancaran]"
                                            value="{{ old('surat.' . $index . '.kelancaran', $s->kelancaran) }}"
                                            class="w-14 border text-center text-xs" min="0" max="33"
                                            oninput="hitungNilai({{ $index }})" required>
                                    </td>
                                    <td class="border px-2 py-1">
                                        <input type="number" name="surat[{{ $index }}][fasohah]"
                                            value="{{ old('surat.' . $index . '.fasohah', $s->fasohah) }}"
                                            class="w-14 border text-center text-xs" min="0" max="33"
                                            oninput="hitungNilai({{ $index }})" required>
                                    </td>
                                    <td class="border px-2 py-1">
                                        <input type="number" name="surat[{{ $index }}][tajwid]"
                                            value="{{ old('surat.' . $index . '.tajwid', $s->tajwid) }}"
                                            class="w-14 border text-center text-xs" min="0" max="33"
                                            oninput="hitungNilai({{ $index }})" required>
                                    </td>
                                    <td class="border px-2 py-1">
                                        <input type="number" value="{{ $s->total_kesalahan }}"
                                            class="w-16 border text-center text-xs bg-gray-100" readonly>
                                    </td>
                                    <td class="border px-2 py-1">
                                        <input type="number" value="{{ $s->nilai }}"
                                            class="w-16 border text-center text-xs bg-gray-100" readonly>
                                    </td>
                                    <td class="border px-2 py-1">{{ $s->predikat }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Rekap -->
                <div class="mt-4 text-sm border border-black p-3 bg-gray-50">
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
                    <p><strong>Total Nilai:</strong> {{ number_format($totalNilai, 1) }}</p>
                    <p><strong>Rata-rata:</strong> {{ number_format($rataRata, 1) }}</p>
                    <p><strong>Predikat Akhir:</strong> {{ $predikatFinal }}</p>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-semibold">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
