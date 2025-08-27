<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa - {{ $student->nama }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 10px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .nilai {
            font-weight: bold;
        }

        .green {
            color: green;
        }

        .blue {
            color: blue;
        }

        .indigo {
            color: indigo;
        }

        .yellow {
            color: goldenrod;
        }

        .orange {
            color: darkorange;
        }

        .red {
            color: red;
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

    <p style="margin-top: 20px; font-size: 11px; text-align: center; color: gray;">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </p>
</body>

</html>
