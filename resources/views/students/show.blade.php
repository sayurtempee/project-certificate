@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="container my-5">
        <h3>Detail & Edit Nilai - {{ $student->nama }}</h3>
        <p><strong>No Induk:</strong> {{ $student->no_induk }}</p>
        <p><strong>Penyimak:</strong> {{ $student->penyimak }}</p>
        <p><strong>Juz:</strong> {{ $student->juz }}</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('student.updateInline', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-bordered mt-3" id="suratTable">
                <thead>
                    <tr>
                        <th>Surat ke</th>
                        <th>Nama Surat</th>
                        <th>Jumlah Ayat</th>
                        <th>Kelancaran</th>
                        <th>Fasohah</th>
                        <th>Tajwid</th>
                        <th>Total Kesalahan</th>
                        <th>Nilai</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->surats as $index => $s)
                        <tr>
                            <td>
                                <input type="hidden" name="surat[{{ $index }}][id]" value="{{ $s->id }}">
                                <input type="hidden" name="surat[{{ $index }}][surat_ke]"
                                    value="{{ $s->surat_ke }}">
                                {{ $s->surat_ke }}
                            </td>
                            <td>
                                <input type="hidden" name="surat[{{ $index }}][nama]"
                                    value="{{ $s->nama_surat }}">
                                {{ $s->nama_surat }}
                            </td>
                            <td>
                                <input type="hidden" name="surat[{{ $index }}][ayat]" value="{{ $s->ayat }}">
                                {{ $s->ayat }}
                            </td>
                            <td>
                                <input type="number" name="surat[{{ $index }}][kelancaran]"
                                    value="{{ old('surat.' . $index . '.kelancaran', $s->kelancaran) }}"
                                    class="form-control @error('surat.' . $index . '.kelancaran') is-invalid @enderror"
                                    min="0" max="33" oninput="hitungNilai({{ $index }})" required>
                                @error('surat.' . $index . '.kelancaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="surat[{{ $index }}][fasohah]"
                                    value="{{ old('surat.' . $index . '.fasohah', $s->fasohah) }}"
                                    class="form-control @error('surat.' . $index . '.fasohah') is-invalid @enderror"
                                    min="0" max="33" oninput="hitungNilai({{ $index }})" required>
                                @error('surat.' . $index . '.fasohah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="surat[{{ $index }}][tajwid]"
                                    value="{{ old('surat.' . $index . '.tajwid', $s->tajwid) }}"
                                    class="form-control @error('surat.' . $index . '.tajwid') is-invalid @enderror"
                                    min="0" max="33" oninput="hitungNilai({{ $index }})" required>
                                @error('surat.' . $index . '.tajwid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="surat[{{ $index }}][total_kesalahan]"
                                    class="form-control bg-light" value="{{ $s->total_kesalahan }}" readonly>
                            </td>
                            <td>
                                <input type="number" name="surat[{{ $index }}][nilai]"
                                    class="form-control bg-light" value="{{ $s->nilai }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="surat[{{ $index }}][predikat]"
                                    class="form-control bg-light" value="{{ $s->predikat }}" readonly>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        function hitungNilai(index) {
            const row = document.querySelectorAll('#suratTable tbody tr')[index];

            const kelancaran = parseInt(row.querySelector(`input[name="surat[${index}][kelancaran]"]`).value) || 0;
            const fasohah = parseInt(row.querySelector(`input[name="surat[${index}][fasohah]"]`).value) || 0;
            const tajwid = parseInt(row.querySelector(`input[name="surat[${index}][tajwid]"]`).value) || 0;

            const totalKesalahan = kelancaran + fasohah + tajwid;
            row.querySelector(`input[name="surat[${index}][total_kesalahan]"]`).value = totalKesalahan;

            const nilai = Math.max(0, 100 - totalKesalahan);
            row.querySelector(`input[name="surat[${index}][nilai]"]`).value = nilai;

            let predikat = "D";
            if (nilai >= 96) predikat = "A+";
            else if (nilai >= 90) predikat = "A";
            else if (nilai >= 86) predikat = "B+";
            else if (nilai >= 80) predikat = "B";
            else if (nilai >= 74.5) predikat = "C";

            row.querySelector(`input[name="surat[${index}][predikat]"]`).value = predikat;
        }
    </script>
@endsection
