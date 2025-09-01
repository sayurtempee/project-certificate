@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-2xl font-bold text-slate-800 mb-4">📖 Detail & Edit Nilai - {{ $student->nama }}</h3>

            <div class="mb-4 space-y-1 text-sm text-slate-600">
                <p><strong>No Induk:</strong> {{ $student->no_induk }}</p>
                <p><strong>Penyimak:</strong> {{ $student->penyimak ?? ($student->penyimak = auth()->user()->name) }}</p>
                <p><strong>Juz:</strong> {{ $student->juz }}</p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-300 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('student.updateInline', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Hidden input untuk Juz --}}
                <input type="hidden" name="juz" value="{{ $student->juz ?? '' }}">

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full text-sm" id="suratTable">
                        <thead class="bg-slate-800 text-white text-sm uppercase">
                            <tr>
                                <th class="px-4 py-2 text-center">Surat ke</th>
                                <th class="px-4 py-2 text-center">Nama Surat</th>
                                <th class="px-4 py-2 text-center">Jumlah Ayat</th>
                                <th class="px-4 py-2 text-center">Kelancaran</th>
                                <th class="px-4 py-2 text-center">Fasohah</th>
                                <th class="px-4 py-2 text-center">Tajwid</th>
                                <th class="px-4 py-2 text-center">Total Kesalahan</th>
                                <th class="px-4 py-2 text-center">Nilai</th>
                                <th class="px-4 py-2 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($surats as $index => $s)
                                <tr class="hover:bg-slate-50 transition text-center">
                                    <td class="px-4 py-2">
                                        <input type="hidden" name="surat[{{ $index }}][id]"
                                            value="{{ $s->id }}">
                                        <input type="hidden" name="surat[{{ $index }}][surat_ke]"
                                            value="{{ $s->surat_ke }}">
                                        {{ $s->surat_ke }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="hidden" name="surat[{{ $index }}][nama]"
                                            value="{{ $s->nama_surat }}">
                                        {{ $s->nama_surat }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="hidden" name="surat[{{ $index }}][ayat]"
                                            value="{{ $s->ayat }}">
                                        {{ $s->ayat }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="surat[{{ $index }}][kelancaran]"
                                            value="{{ old('surat.' . $index . '.kelancaran', $s->kelancaran) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50
                                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="surat[{{ $index }}][fasohah]"
                                            value="{{ old('surat.' . $index . '.fasohah', $s->fasohah) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50
                                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="surat[{{ $index }}][tajwid]"
                                            value="{{ old('surat.' . $index . '.tajwid', $s->tajwid) }}"
                                            class="w-20 rounded-md border border-yellow-300 bg-yellow-50
                                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition text-center"
                                            min="0" max="33" oninput="hitungNilai({{ $index }})"
                                            required>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" class="w-24 bg-gray-100 border-gray-200 rounded-md"
                                            value="{{ $s->total_kesalahan }}" readonly>
                                        <input type="hidden" name="surat[{{ $index }}][total_kesalahan]"
                                            value="{{ $s->total_kesalahan }}">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" class="w-20 bg-gray-100 border-gray-200 rounded-md"
                                            value="{{ $s->nilai }}" readonly>
                                        <input type="hidden" name="surat[{{ $index }}][nilai]"
                                            value="{{ $s->nilai }}">
                                    </td>

                                    {{--  class warna predikat  --}}
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
                                    <td class="px-4 py-2 text-center">
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

                <button type="submit"
                    class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                    💾 Simpan Perubahan
                </button>
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

            const nilai = Math.max(0, 100 - totalKesalahan);
            row.querySelector(`input[name="surat[${index}][nilai]"]`).value = nilai;

            // Tentukan predikat
            let predikat = "D";
            if (nilai >= 96) predikat = "A+";
            else if (nilai >= 90) predikat = "A";
            else if (nilai >= 86) predikat = "B+";
            else if (nilai >= 80) predikat = "B";
            else if (nilai >= 74.5) predikat = "C";

            // Update hidden input predikat (untuk submit ke server)
            row.querySelector(`input[name="surat[${index}][predikat]"]`).value = predikat;

            // Update tampilan badge predikat (tanpa reload)
            row.querySelector(`.predikat-badge`).innerHTML = getPredikatBadge(predikat);
        }
    </script>
@endsection
