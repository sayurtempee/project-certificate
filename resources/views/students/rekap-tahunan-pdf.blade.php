<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Siswa {{ $tahun }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .page-break {
            page-break-before: always;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    @foreach ($groupedStudents as $penyimak => $students)
        <div class="{{ !$loop->first ? 'page-break' : '' }}">
            <h2>Rekap Nilai Siswa Tahun {{ $tahun }}</h2>
            <h3>Penyimak: {{ $penyimak }}</h3>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No Induk</th>
                        <th>Juz</th>
                        <th>Rata-rata Nilai</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $i => $student)
                        @php
                            $avg = $student->surats->avg('nilai') ?? 0;
                            $predikat =
                                $avg >= 96
                                    ? 'A+'
                                    : ($avg >= 90
                                        ? 'A'
                                        : ($avg >= 86
                                            ? 'B+'
                                            : ($avg >= 80
                                                ? 'B'
                                                : ($avg >= 74.5
                                                    ? 'C'
                                                    : 'D'))));
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $student->nama }}</td>
                            <td>{{ $student->no_induk }}</td>
                            <td>{{ $student->juz }}</td>
                            <td>{{ number_format($avg, 2) }}</td>
                            <td>{{ $predikat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
