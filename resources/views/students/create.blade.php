@extends('layouts.dashboard')

@section('title', 'Tambah Murid')

@section('dashboard-content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Form Tambah Murid</h2>

        <form action="{{ route('student.store') }}" method="POST" id="formSertifikat">
            @csrf

            {{-- Nama Murid --}}
            <label class="block">Nama Murid</label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded p-2 mb-3">
            @error('nama')
                <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
            @enderror

            {{-- No Induk --}}
            <label class="block">No Induk</label>
            <input type="text" name="no_induk" value="{{ old('no_induk') }}" class="w-full border rounded p-2 mb-3">
            @error('no_induk')
                <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
            @enderror

            {{-- Penyimak (otomatis guru login) --}}
            <label class="block">Penyimak</label>
            <input type="text" name="penyimak" value="{{ auth()->user()->name }}"
                class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>

            {{-- Juz --}}
            <label class="block">Juz</label>
            <select name="juz" id="juzSelect" class="w-full border rounded p-2 mb-3">
                <option value="">-- Pilih Juz --</option>
                @foreach ($juzData as $juz => $surat)
                    <option value="{{ $juz }}" {{ old('juz') == $juz ? 'selected' : '' }}>Juz {{ $juz }}
                    </option>
                @endforeach
            </select>
            @error('juz')
                <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
            @enderror

            {{-- Tabel Surat --}}
            <table class="w-full border mb-4" id="suratTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Surat ke-</th>
                        <th class="p-2 border">Nama Surat</th>
                        <th class="p-2 border">Jumlah Ayat</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            {{-- Input Manual --}}
            <label>Kelancaran</label>
            <input type="number" id="kelancaran" name="kelancaran" class="w-full border rounded p-2 mb-2">

            <label>Fasohah</label>
            <input type="number" id="fasohah" name="fasohah" class="w-full border rounded p-2 mb-2">

            <label>Tajwid</label>
            <input type="number" id="tajwid" name="tajwid" class="w-full border rounded p-2 mb-2">

            <label>Total Kesalahan</label>
            <input type="number" id="totalKesalahan" name="total_kesalahan"
                class="w-full border rounded p-2 mb-2 bg-gray-100" readonly>

            <label>Nilai</label>
            <input type="number" id="nilai" name="nilai" class="w-full border rounded p-2 mb-2 bg-gray-100" readonly>

            <label>Predikat</label>
            <input type="text" id="predikat" name="predikat" class="w-full border rounded p-2 mb-4 bg-gray-100"
                readonly>

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
                            <td class="border p-2">${surat['surat-ke']}</td>
                            <td class="border p-2">${surat.nama}</td>
                            <td class="border p-2">${surat.ayat}</td>
                        </tr>
                    `;
                });
            }
        });

        function hitungNilai() {
            const kelancaran = parseInt(document.getElementById('kelancaran').value) || 0;
            const fasohah = parseInt(document.getElementById('fasohah').value) || 0;
            const tajwid = parseInt(document.getElementById('tajwid').value) || 0;

            const totalKesalahan = kelancaran + fasohah + tajwid;
            document.getElementById('totalKesalahan').value = totalKesalahan;

            const nilai = Math.max(0, 100 - totalKesalahan);
            document.getElementById('nilai').value = nilai;

            let predikat = "D";
            if (nilai >= 96) predikat = "A+";
            else if (nilai >= 90) predikat = "A";
            else if (nilai >= 86) predikat = "B+";
            else if (nilai >= 80) predikat = "B";
            else if (nilai >= 74.5) predikat = "C";

            document.getElementById('predikat').value = predikat;
        }

        document.getElementById('kelancaran').addEventListener('input', hitungNilai);
        document.getElementById('fasohah').addEventListener('input', hitungNilai);
        document.getElementById('tajwid').addEventListener('input', hitungNilai);
    </script>
@endsection
