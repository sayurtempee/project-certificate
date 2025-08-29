@extends('layouts.dashboard')

@section('title', 'Tambah Murid')

@section('dashboard-content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Form Tambah Murid</h2>

        <form action="{{ route('student.store') }}" method="POST">
            @csrf

            {{-- Nama Murid --}}
            <label class="block">Nama Murid</label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded p-2 mb-3">

            {{-- No Induk --}}
            <label class="block">No Induk</label>
            <input type="text" name="no_induk" value="{{ old('no_induk') }}" class="w-full border rounded p-2 mb-3">

            {{-- Penyimak --}}
            <label class="block">Penyimak</label>
            <input type="text" name="penyimak" value="{{ auth()->user()->name }}"
                class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>

            {{-- Juz --}}
            <label class="block">Juz</label>
            <select name="juz" id="juzSelect" class="w-full border rounded p-2 mb-3">
                <option value="">-- Pilih Juz --</option>
                @foreach ($juzData as $juz => $surat)
                    <option value="{{ $juz }}">Juz {{ $juz }}</option>
                @endforeach
            </select>

            {{-- Tabel Surat --}}
            <table class="w-full border mb-4" id="suratTable">
                <thead>
                    <tr class="bg-gray-100">
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

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
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
                    <tr>
                        <td class="border p-2">
                            <input type="hidden" name="surat[${index}][surat_ke]" value="${surat['surat-ke']}">
                            ${surat['surat-ke']}
                        </td>
                        <td class="border p-2">
                            <input type="hidden" name="surat[${index}][nama]" value="${surat.nama}">
                            ${surat.nama}
                        </td>
                        <td class="border p-2">
                            <input type="hidden" name="surat[${index}][ayat]" value="${surat.ayat}">
                            ${surat.ayat}
                        </td>
                        <td class="border p-2">
                            <input type="number" name="surat[${index}][kelancaran]"
                                    class="w-20 border rounded p-1" oninput="hitungNilai(${index})" min="0" max="33">
                        </td>
                        <td class="border p-2">
                            <input type="number" name="surat[${index}][fasohah]"
                                    class="w-20 border rounded p-1" oninput="hitungNilai(${index})" min="0" max="33">
                        </td>
                        <td class="border p-2">
                            <input type="number" name="surat[${index}][tajwid]"
                                    class="w-20 border rounded p-1" oninput="hitungNilai(${index})" min="0" max="33">
                        </td>
                        <td class="border p-2">
                            <input type="number" name="surat[${index}][total_kesalahan]"
                                    class="w-20 border rounded p-1 bg-gray-100" readonly>
                        </td>
                        <td class="border p-2">
                            <input type="number" name="surat[${index}][nilai]"
                                    class="w-20 border rounded p-1 bg-gray-100" readonly>
                        </td>
                        <td class="border p-2">
                            <input type="text" name="surat[${index}][predikat]"
                                    class="w-20 border rounded p-1 bg-gray-100" readonly>
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

            const nilai = Math.max(0, 100 - totalKesalahan);
            row.querySelector(`[name="surat[${index}][nilai]"]`).value = nilai;

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
