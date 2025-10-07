<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
        }

        .certificate {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 550px;
            padding: 40px 60px;
            background: url("{{ public_path('img/template/certificate.jpeg') }}") no-repeat center center;
            background-size: cover;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-left img,
        .header-right img {
            height: 60px;
            width: auto;
        }

        .school-info {
            text-align: center;
            flex: 1;
            margin: 0 15px;
        }

        .school-info h2 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .school-info p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* Title */
        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h1 {
            font-size: 26px;
            font-weight: bold;
            margin: 0;
        }

        .title p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* Student */
        .student-name {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
        }

        .details {
            text-align: center;
            font-size: 14px;
            margin: 5px 0;
        }

        .score {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
        }

        .footer .date-place {
            margin-bottom: 40px;
        }

        .footer .signature {
            font-weight: bold;
            margin-top: 10px;
        }

        /* Decorative images */
        .decor-left {
            position: absolute;
            bottom: 20px;
            left: 0;
            height: 100px;
        }

        .decor-right {
            position: absolute;
            bottom: 20px;
            right: 20px;
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('img/template/logo_left.png') }}" alt="Logo Kiri">
            </div>
            <div class="school-info">
                <h2>SDI AL AZHAR 13 RAWAMANGUN</h2>
                <p>Jl. Sunan Giri No. 1 Rawamangun 13220 Jakarta Timur DKI Jakarta - INDONESIA</p>
                <p>Telp. 021 4786 7777 • e-mail: info@sdialazhar13.sch.id • website: sdialazhar13.sch.id</p>
            </div>
            <div class="header-right">
                <img src="{{ public_path('img/template/logo_right.png') }}" alt="Logo Kanan">
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            <h1>SERTIFIKAT</h1>
            <p>Diberikan kepada</p>
        </div>

        <!-- Student Name -->
        <div class="student-name">{{ $student->nama }}</div>

        <!-- Details -->
        <div class="details">
            <p>No. Induk: {{ $student->no_induk }}</p>
            <p>Telah menyelesaikan penyimakan Juz <strong>{{ $student->juz }}</strong></p>
        </div>

        <!-- Score -->
        @php
            $nilai = $student->surats->avg('nilai');
        @endphp
        <div class="score">Nilai Akhir: {{ $nilai ? number_format($nilai, 0) : '-' }}</div>

        <!-- Footer -->
        <div class="footer">
            <div class="date-place">
                {{ $student->tempat_kelulusan ?? 'Tempat Belum Ditetapkan' }},
                {{ $student->tanggal_lulus ? $student->tanggal_lulus->translatedFormat('d F Y') : 'Tanggal Belum Ditetapkan' }}
            </div>
            Kepala Sekolah
            <div class="signature">
                {{ $certificate['nama_kepala_sekolah'] ?? 'Nama Belum Ditetapkan' }}<br>
                NIP: {{ $certificate['nip'] ?? 'NIP Belum Ditetapkan' }}
            </div>
        </div>

        <!-- Decorative images -->
        <img class="decor-left" src="{{ public_path('img/template/decor_left.png') }}" alt="Dekorasi Kiri">
        <img class="decor-right" src="{{ public_path('img/template/decor_right.png') }}" alt="Dekorasi Kanan">
    </div>
</body>

</html>
