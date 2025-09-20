<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transkip Nilai TASMI - {{ $student->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info {
            margin: 15px 0;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            font-weight: bold;
        }

        .left {
            text-align: left;
        }

        .nilai {
            font-weight: bold;
        }

        .rekap td {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>TRANSKIP NILAI TASMI' JUZ {{ $student->juz }} 1 KALI DUDUK <br>
        SDI AL AZHAR 13 RAWAMANGUN {{ now()->subYear()->format('Y') }}/{{ now()->format('Y') }}</h2>

    {{-- Data siswa --}}
    <div class="info">
        <p>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $student->nama }}</p>
        <p>No Induk : {{ $student->no_induk }}</p>
        <p>Penyimak : {{ $student->penyimak ?? auth()->user()->name }}</p>
    </div>

    {{-- Daftar surat --}}
    <table>
        <thead>
            <tr>
                <th style="width:10%">Surat Ke</th>
                <th style="width:40%">Nama Surat</th>
                <th style="width:15%">Jumlah Ayat</th>
                <th style="width:15%">Nilai</th>
                <th style="width:10%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surats as $s)
                <tr>
                    <td>{{ $s->surat_ke }}</td>
                    <td class="left">{{ $s->nama_surat }}</td>
                    <td>{{ $s->ayat }}</td>
                    <td class="nilai">
                        {{ fmod($s->nilai, 1) == 0 ? number_format($s->nilai, 1) : rtrim(rtrim(number_format($s->nilai, 2), '0'), '.') }}
                    </td>
                    <td>{{ $s->predikat }}</td>
                </tr>
            @endforeach

            @php
                $totalNilai = $surats->sum('nilai');
                $rataRata = $surats->count() > 0 ? $totalNilai / $surats->count() : 0;

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
                } else {
                    $predikatFinal = 'D';
                }
            @endphp

            {{-- Total + Rata-rata gabung predikat --}}
            <tr class="rekap">
                <td colspan="3">TOTAL</td>
                <td>{{ number_format($totalNilai, 1) }}</td>
                <td rowspan="2" style="font-weight:bold;">{{ $predikatFinal }}</td>
            </tr>
            <tr class="rekap">
                <td colspan="3">NILAI RATA-RATA</td>
                <td>{{ number_format($rataRata, 1) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tempat & tanda tangan --}}
    <table style="width: 100%; margin-top: 50px; border-collapse: collapse; text-align: center;">
        <tr>
            <td style="width: 50%; border: 1px solid #000; padding: 15px;">
                Orang Tua / Wali
            </td>
            <td style="width: 50%; border: 1px solid #000; padding: 15px;">
                Penyimak
            </td>
        </tr>
        <tr>
            <td style="height: 80px; border: 1px solid #000; vertical-align: bottom;">
                ( .......................... )
            </td>
            <td style="height: 80px; border: 1px solid #000; vertical-align: bottom;">
                ({{ $student->penyimak ?? auth()->user()->name }})
            </td>
        </tr>
    </table>
</body>

</html>
