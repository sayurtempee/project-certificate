@extends('layouts.dashboard')

@section('title', 'Edit Siswa')

@section('dashboard-content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Data Siswa</h2>

        <form action="{{ route('student.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <label class="block">Nama Siswa</label>
            <input type="text" name="nama" value="{{ old('nama', $student->nama) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- No Induk --}}
            <label class="block">No Induk</label>
            <input type="text" name="no_induk" value="{{ old('no_induk', $student->no_induk) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- Penyimak --}}
            <label class="block">Penyimak</label>
            @if (auth()->user()->role === 'teacher')
                <input type="text" name="penyimak" value="{{ auth()->user()->name }}"
                    class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>
            @else
                <select name="penyimak" class="w-full border rounded p-2 mb-3">
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->name }}" {{ $student->penyimak == $teacher->name ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            @endif

            {{-- Juz --}}
            <label class="block">Juz</label>
            <input type="number" name="juz" value="{{ old('juz', $student->juz) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- Kelancaran --}}
            <label class="block">Kelancaran</label>
            <input type="number" name="kelancaran" id="kelancaran" value="{{ old('kelancaran', $student->kelancaran) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- Fasohah --}}
            <label class="block">Fasohah</label>
            <input type="number" name="fasohah" id="fasohah" value="{{ old('fasohah', $student->fasohah) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- Tajwid --}}
            <label class="block">Tajwid</label>
            <input type="number" name="tajwid" id="tajwid" value="{{ old('tajwid', $student->tajwid) }}"
                class="w-full border rounded p-2 mb-3">

            {{-- Total Kesalahan --}}
            <label class="block">Total Kesalahan</label>
            <input type="number" name="total_kesalahan" id="totalKesalahan"
                value="{{ old('total_kesalahan', $student->total_kesalahan) }}"
                class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>

            {{-- Nilai --}}
            <label class="block">Nilai</label>
            <input type="number" name="nilai" id="nilai" value="{{ old('nilai', $student->nilai) }}"
                class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>

            {{-- Predikat --}}
            <label class="block">Predikat</label>
            <input type="text" name="predikat" id="predikat" value="{{ old('predikat', $student->predikat) }}"
                class="w-full border rounded p-2 mb-3 bg-gray-100" readonly>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
    <script>
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

        // Hitung langsung saat form pertama kali dibuka
        window.addEventListener('DOMContentLoaded', hitungNilai);
    </script>
@endsection
