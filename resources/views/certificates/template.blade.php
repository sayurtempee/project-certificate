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
            min-height: 530px;
            padding: 40px 60px;
            background: url("{{ public_path('img/template/certificate.jpeg') }}") no-repeat center center;
            background-size: cover;
        }

        .title {
            text-align: center;
            margin-top: 40px;
        }

        .title h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .title p {
            font-size: 14px;
            margin: 2px 0;
        }

        .student-name {
            text-align: center;
            margin: 15px 0;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
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
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 80px;
            font-size: 14px;
        }

        .footer .signature {
            margin-top: 60px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <!-- Judul -->
        <div class="title">
            <h1>SERTIFIKAT</h1>
            <p>Diberikan kepada</p>
        </div>

        <!-- Nama Siswa -->
        <div class="student-name">{{ $student->nama }}</div>

        <!-- Detail -->
        <div class="details">
            <p>No. Induk: {{ $student->no_induk }}</p>
            <p>Telah menyelesaikan penyimakan Juz <strong>{{ $student->juz }}</strong></p>
        </div>

        <!-- Nilai -->
        @php
            $nilai = $student->surats->avg('nilai');
        @endphp
        <div class="score">
            Nilai Akhir: {{ $nilai ? number_format($nilai, 0) : '-' }}
        </div>

        <!-- Tanda Tangan -->
        <div class="footer">
            <span>{{ $student['tempat_kelulusan'] }}, {{ $student->tanggal_lulus->translatedFormat('d F Y') }}</span>
            <br>
            Kepala Sekolah
            <div class="signature">
                {{ $certificate['nama_kepala_sekolah'] }}<br>
                NIP: {{ $certificate['nip'] }}
            </div>
        </div>
    </div>
</body>

</html>
