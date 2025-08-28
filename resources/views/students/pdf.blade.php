<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa - {{ $student->nama }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            color: #444;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        td {
            background: #fff;
        }

        .nilai {
            font-weight: bold;
            font-size: 13px;
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
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: gray;
        }

        /* Tambahan: table zebra effect */
        tr:nth-child(even) td {
            background: #fdfdfd;
        }

        tr:hover td {
            background: #f1f5f9;
        }
    </style>
</head>

<body>
    <h2>Data Penilaian Siswa</h2>

    <table>
        <tr>
            <th>Nama Siswa</th>
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
        @if (isset($juzData[$student->juz]))
            <tr>
                <th>Daftar Surat</th>
                <td>
                    <table style="width:100%; border-collapse: collapse; margin-top: 5px; font-size: 11px;">
                        <thead>
                            <tr style="background:#f2f2f2;">
                                <th style="border:1px solid #ccc; padding:5px;">Surat ke-</th>
                                <th style="border:1px solid #ccc; padding:5px;">Nama Surat</th>
                                <th style="border:1px solid #ccc; padding:5px;">Jumlah Ayat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($juzData[$student->juz] as $surat)
                                <tr>
                                    <td style="border:1px solid #ccc; padding:5px;">{{ $surat['surat-ke'] }}</td>
                                    <td style="border:1px solid #ccc; padding:5px;">{{ $surat['nama'] }}</td>
                                    <td style="border:1px solid #ccc; padding:5px;">{{ $surat['ayat'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <th>Penyimak</th>
            <td>{{ $student->penyimak }}</td>
        </tr>
        <tr>
            <th>Kelancaran</th>
            <td>{{ $student->kelancaran }}</td>
        </tr>
        <tr>
            <th>Fasohah</th>
            <td>{{ $student->fasohah }}</td>
        </tr>
        <tr>
            <th>Tajwid</th>
            <td>{{ $student->tajwid }}</td>
        </tr>
        <tr>
            <th>Total Kesalahan</th>
            <td>{{ $student->total_kesalahan }}</td>
        </tr>
        <tr>
            <th>Nilai</th>
            <td class="nilai">{{ $student->nilai }}</td>
        </tr>
        <tr>
            <th>Predikat</th>
            <td
                class="nilai
                @if ($student->nilai >= 96) green
                @elseif($student->nilai >= 90) blue
                @elseif($student->nilai >= 86) indigo
                @elseif($student->nilai >= 80) yellow
                @elseif($student->nilai >= 74.5) orange
                @else red @endif">
                {{ $student->predikat }}
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
