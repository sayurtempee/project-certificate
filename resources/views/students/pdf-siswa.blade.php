<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transkip Nilai TASMI - {{ $student->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            position: relative;
            min-height: 100%;
            padding-bottom: 2.5cm;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: #2c3e50;
            text-transform: uppercase;
        }

        /* =======================
           DATA SISWA
        ======================= */
        .student-info {
            font-size: 11px;
            margin-bottom: 15px;
            border: none;
            width: auto;
        }

        .student-info td {
            border: none;
            padding: 1px 3px;
            vertical-align: top;
        }

        .student-info td:first-child {
            width: 120px;
            white-space: nowrap;
            text-align: right;
            padding-right: 10px;
        }

        .student-info td:last-child {
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 4px 6px;
            text-align: center;
            font-size: 9px;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .nilai {
            font-weight: bold;
            font-family: monospace;
            text-align: center;
        }

        .predikat {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
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

        .footer {
            font-size: 9px;
            text-align: center;
            color: gray;
            position: absolute;
            bottom: 2cm;
            left: 2cm;
            right: 2cm;
        }

        .signature-section {
            width: 100%;
            margin-top: 40px;
            font-size: 10px;
        }

        .signature-table {
            width: 100%;
            border: none;
            margin-top: 20px;
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: bottom;
            height: 80px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h2>TRANSKIP NILAI TASMI 1 KALI DUDUK <br>
        SDI AL - AZHAR 13 RAWAMANGUN {{ now()->subYear()->format('Y') }} / {{ now()->format('Y') }}
    </h2>

    {{-- Data siswa --}}
    <table class="student-info">
        <tr>
            <td>Nama Murid :</td>
            <td>{{ $student->nama }}</td>
        </tr>
        <tr>
            <td>Juz :</td>
            <td>Juz {{ $student->juz }}</td>
        </tr>
        <tr>
            <td>No Induk :</td>
            <td>{{ $student->no_induk }}</td>
        </tr>
        <tr>
            <td>Penyimak :</td>
            <td>{{ $student->penyimak ?? auth()->user()->name }}</td>
        </tr>
    </table>

    {{-- Daftar surat --}}
    <table>
        <thead>
            <tr>
                <th style="width:10%">Surat ke</th>
                <th style="width:40%; text-align:left">Nama Surat</th>
                <th style="width:12%">Jumlah Ayat</th>
                <th style="width:18%">Nilai</th>
                <th style="width:10%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surats as $s)
                <tr>
                    <td>{{ $s->surat_ke }}</td>
                    <td style="text-align:left">{{ $s->nama_surat }}</td>
                    <td>{{ $s->ayat }}</td>
                    <td class="nilai">
                        {{ fmod($s->nilai, 1) == 0 ? number_format($s->nilai, 1) : rtrim(rtrim(number_format($s->nilai, 2), '0'), '.') }}
                    </td>
                    <td
                        class="predikat
                    @if ($s->predikat == 'A+') green
                    @elseif($s->predikat == 'A') blue
                    @elseif($s->predikat == 'B+') indigo
                    @elseif($s->predikat == 'B') yellow
                    @elseif($s->predikat == 'C') orange
                    @else red @endif">
                        {{ $s->predikat }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tempat & tanda tangan --}}
    <div class="signature-section">
        <div style="text-align: right;">
            Jakarta, {{ now()->translatedFormat('d F Y') }}
        </div>
        <table class="signature-table">
            <tr>
                <td>Orang Tua / Wali</td>
                <td>Penyimak</td>
            </tr>
            <tr>
                <td style="padding-top: 60px;">(............................)</td>
                <td style="padding-top: 60px;">({{ $student->penyimak ?? auth()->user()->name }})</td>
            </tr>
        </table>
    </div>

    {{-- Footer cetak --}}
    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
