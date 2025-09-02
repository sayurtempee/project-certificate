<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transkip Nilai TASMI - {{ $student->nama }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: #2c3e50;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: gray;
        }

        .nilai {
            font-weight: bold;
        }

        .green {
            color: #16a34a;
        }

        .blue {
            color: #2563eb;
        }

        .indigo {
            color: #4f46e5;
        }

        .yellow {
            color: #b45309;
        }

        .orange {
            color: #ea580c;
        }

        .red {
            color: #dc2626;
        }
    </style>
</head>

<body>
    <h2>TRANSKIP NILAI TASMI 1 KALI DUDUK </br> SDI AL - AZHAR 13 RAWAMANGUN {{ now()->subYear()->format('Y') }} /
        {{ now()->format('Y') }}</h2>

    <table style="margin-bottom:15px;">
        <tr>
            <th style="width:30%">Nama Siswa</th>
            <td>{{ $student->nama }}</td>
        </tr>
        <tr>
            <th>No Induk</th>
            <td>{{ $student->no_induk }}</td>
        </tr>
        <tr>
            <th>Juz</th>
            <td>Juz {{ $student->juz }}</td>
        </tr>
        <tr>
            <th>Penyimak</th>
            <td>{{ $student->penyimak ?? ($student->penyimak = auth()->user()->name) }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Surat ke</th>
                <th>Nama Surat</th>
                <th>Jumlah Ayat</th>
                {{--  <th>Kelancaran</th>  --}}
                {{--  <th>Fasohah</th>  --}}
                {{--  <th>Tajwid</th>  --}}
                {{--  <th>Total Kesalahan</th>  --}}
                <th>Nilai</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surats as $s)
                <tr>
                    <td>{{ $s->surat_ke }}</td>
                    <td style="text-align:left">{{ $s->nama_surat }}</td>
                    <td>{{ $s->ayat }}</td>
                    {{--  <td>{{ $s->kelancaran }}</td>  --}}
                    {{--  <td>{{ $s->fasohah }}</td>  --}}
                    {{--  <td>{{ $s->tajwid }}</td>  --}}
                    {{--  <td>{{ $s->total_kesalahan }}</td>  --}}
                    <td class="nilai">{{ $s->nilai }}</td>
                    <td
                        class="nilai
                        @if ($s->nilai >= 96) green
                        @elseif($s->nilai >= 90) blue
                        @elseif($s->nilai >= 86) indigo
                        @elseif($s->nilai >= 80) yellow
                        @elseif($s->nilai >= 74.5) orange
                        @else red @endif">
                        {{ $s->predikat }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
