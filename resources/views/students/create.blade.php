@extends('layouts.dashboard')

@section('title', 'Tambah Murid')

@section('dashboard-content')
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                👨‍🎓 Form Tambah Murid
            </h2>
            <a href="{{ route('student.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                ⬅️ Kembali
            </a>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-xl shadow p-6">
            <form action="{{ route('student.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Murid --}}
                <div>
                    <label class="block font-semibold mb-1">Nama Murid</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300">
                </div>

                {{-- No Induk --}}
                <div>
                    <label class="block font-semibold mb-1">No Induk</label>
                    <input type="text" name="no_induk" value="{{ old('no_induk') }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300">
                </div>

                {{-- Penyimak --}}
                <div>
                    <label class="block font-semibold mb-1">Penyimak</label>
                    <input type="text" name="penyimak" value="{{ auth()->user()->name }}"
                        class="w-full border rounded-lg p-2 bg-gray-100" readonly>
                </div>

                {{-- Juz --}}
                <div>
                    <label class="block font-semibold mb-1">Juz</label>
                    <select name="juz" id="juzSelect"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Pilih Juz --</option>
                        @foreach ($juzData as $juz => $surat)
                            <option value="{{ $juz }}">Juz {{ $juz }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Surat --}}
                <div class="overflow-x-auto">
                    <table class="w-full border rounded-lg overflow-hidden text-sm" id="suratTable">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="p-2 border">Surat ke-</th>
                                <th class="p-2 border">Nama Surat</th>
                                <th class="p-2 border">Jumlah Ayat</th>
                                <th class="p-2 border">Kelancaran</th>
                                <th class="p-2 border">Fasohah</th>
                                <th class="p-2 border">Tajwid</th>
                                <th class="p-2 border">Total Kesalahan</th>
                                <th class="p-2 border">Nilai</th>
                                <th class="p-2 border">Predikat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('student.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const juzData = @json($juzData);

        document.getElementById('juzSelect').addEventListener('change', function() {
            const juz = this.value;
            const tableBody = document.querySelector('#suratTable tbody');
            tableBody.innerHTML = '';

            if (juzData[juz]) {
                juzData[juz].forEach((surat, index) => {
                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50">
                            <td class="border p-2">
                                <input type="hidden" name="surat[${index}][surat_ke]" value="${surat.surat_ke}">
                                ${surat.surat_ke}
                            </td>
                            <td class="border p-2">
                                <input type="hidden" name="surat[${index}][nama_surat]" value="${surat.nama_surat}">
                                ${surat.nama_surat}
                            </td>
                            <td class="border p-2">
                                <input type="hidden" name="surat[${index}][ayat]" value="${surat.ayat}">
                                ${surat.ayat}
                            </td>
                            <td class="border p-2">
                                <input type="number" name="surat[${index}][kelancaran]"
                                       class="w-20 border rounded p-1 text-center"
                                       oninput="hitungNilai(${index})" min="0" max="33">
                            </td>
                            <td class="border p-2">
                                <input type="number" name="surat[${index}][fasohah]"
                                       class="w-20 border rounded p-1 text-center"
                                       oninput="hitungNilai(${index})" min="0" max="33">
                            </td>
                            <td class="border p-2">
                                <input type="number" name="surat[${index}][tajwid]"
                                       class="w-20 border rounded p-1 text-center"
                                       oninput="hitungNilai(${index})" min="0" max="33">
                            </td>
                            <td class="border p-2">
                                <input type="number" name="surat[${index}][total_kesalahan]"
                                       class="w-20 border rounded p-1 bg-gray-100 text-center" readonly>
                            </td>
                            <td class="border p-2">
                                <input type="number" name="surat[${index}][nilai]"
                                       class="w-20 border rounded p-1 bg-gray-100 text-center" readonly>
                            </td>
                            <td class="border p-2">
                                <input type="text" name="surat[${index}][predikat]"
                                       class="w-20 border rounded p-1 bg-gray-100 text-center" readonly>
                            </td>
                        </tr>
                    `;
                });
            }
        });

        function hitungNilai(index) {
            const row = document.querySelectorAll('#suratTable tbody tr')[index];
            const kelancaran = parseInt(row.querySelector(`[name="surat[${index}][kelancaran]"]`).value) || 0;
            const fasohah = parseInt(row.querySelector(`[name="surat[${index}][fasohah]"]`).value) || 0;
            const tajwid = parseInt(row.querySelector(`[name="surat[${index}][tajwid]"]`).value) || 0;

            const totalKesalahan = kelancaran + fasohah + tajwid;
            row.querySelector(`[name="surat[${index}][total_kesalahan]"]`).value = totalKesalahan;

            // Ambil juz yang dipilih
            const juz = parseInt(document.getElementById('juzSelect').value);

            // Tentukan bobot
            let bobot = (juz >= 1 && juz <= 15) ? 1.7 : 1.9;

            // Hitung nilai
            const nilai = Math.max(0, 100 - (totalKesalahan * bobot));
            row.querySelector(`[name="surat[${index}][nilai]"]`).value = nilai.toFixed(2);

            // Tentukan predikat
            let predikat = "D";
            if (nilai >= 96) predikat = "A+";
            else if (nilai >= 90) predikat = "A";
            else if (nilai >= 86) predikat = "B+";
            else if (nilai >= 80) predikat = "B";
            else if (nilai >= 74.5) predikat = "C";

            row.querySelector(`[name="surat[${index}][predikat]"]`).value = predikat;
        }
    </script>
@endsection
