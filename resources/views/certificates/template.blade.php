<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat - {{ $student->nama }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background: url("{{ public_path('img/template/certificate.jpeg') }}") no-repeat center center;
            background-size: cover;
            width: 100%;
            height: 100%;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #000;
            padding: 120px 60px 80px;
        }

        h1 {
            font-size: 38px;
            font-weight: bold;
            margin-top: 60px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin: 4px 0;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
        }

        .details {
            font-size: 18px;
            margin-top: 10px;
        }

        .score {
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 18px;
        }

        .footer .signature {
            margin-top: 90px;
            font-weight: bold;
        }

        .footer .signature span {
            display: block;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>SERTIFIKAT</h1>
        <p>Diberikan kepada</p>

        <div class="student-name">{{ $student->nama }}</div>

        <div class="details">
            <p>No. Induk: {{ $student->no_induk }}</p>
            <p>Telah menyelesaikan penyimakan <strong>Juz {{ $student->juz }}</strong></p>

            @php
                $nilai = $student->surats->avg('nilai');
            @endphp
            <p class="score">Nilai Akhir: {{ $nilai ? number_format($nilai, 0) : '-' }}</p>
        </div>

        <div class="footer">
            <div>
                {{ $student->tempat_kelulusan ?? 'Tempat Belum Ditetapkan' }},
                {{ $student->tanggal_lulus?->translatedFormat('d F Y') ?? 'Tanggal Belum Ditetapkan' }}
            </div>
            <div>Kepala Sekolah</div>
            <div class="signature">
                {{ $student->nm_kepsek ?? 'Nama Belum Ditetapkan' }}
                <span>NIP: {{ $student->nip_kepsek ?? 'NIP Belum Ditetapkan' }}</span>
            </div>
        </div>
    </div>
</body>

</html>
