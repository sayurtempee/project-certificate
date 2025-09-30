<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url("{{ public_path('img/template/certificate.jpeg') }}") no-repeat center center;
            background-size: cover;
            font-family: 'Times New Roman', serif;
        }

        .content {
            position: relative;
            width: 100%;
            height: 905px;
            /* sesuai setPaper height */
            padding: 100px 80px;
            /* sesuaikan dengan posisi */
        }

        .name {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-top: 150px;
        }

        .no-induk {
            font-size: 20px;
            text-align: center;
            margin-top: 10px;
        }

        .description {
            margin-top: 40px;
            text-align: center;
            font-size: 18px;
            line-height: 1.6;
        }

        .footer {
            position: absolute;
            bottom: 80px;
            right: 100px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="name">{{ $student->nama }}</div>
        <div class="no-induk">Nomor Induk: {{ $student->no_induk }}</div>

        <div class="description">
            Yang telah dinyatakan lulus tasmi’ sekali duduk Juz {{ $student->juz }}<br>
            dengan nilai {{ $student->surats->first()->nilai ?? '-' }} ({{ $student->surats->first()->predikat }})<br>
            Pada tanggal {{ $student->tanggal_lulus->translatedFormat('d F Y') }}<br>
            yang telah diprogramkan di SD Islam Al Azhar 13 Rawamangun.<br><br>
            Semoga sertifikat penghargaan ini menjadi motivasi<br>
            untuk terus menghafal Al-Qur’an.
        </div>

        <div class="footer">
            Jakarta, {{ $student->tanggal_lulus->translatedFormat('d F Y') }} <br>
            Kepala Sekolah <br><br><br>
            <strong>Neor Imanah, M.Pd</strong><br>
            NIP: 1234567890
        </div>
    </div>
</body>

</html>
