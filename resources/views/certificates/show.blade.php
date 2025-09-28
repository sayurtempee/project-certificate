@extends('layouts.dashboard')

@section('title', 'Preview Sertifikat')

@section('dashboard-content')
    @include('layouts.partials.swal')

    <div class="container mx-auto px-5 py-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="bi bi-eye"></i>
            Preview Sertifikat
        </h3>

        <div class="relative ml-12" style="width:750px; height:530px; transform: scale(0.95);">
            <!-- Background sertifikat -->
            <img src="{{ asset('img/template/certificate.jpeg') }}" alt="Certificate Template"
                class="absolute inset-0 w-full h-full object-cover rounded-lg">

            <!-- Konten Sertifikat -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-black px-6">
                <h1 class="text-3xl font-bold mb-2">SERTIFIKAT</h1>
                <p class="text-sm mb-1">Diberikan kepada</p>
                <h2 class="text-2xl font-semibold mb-1">{{ $student->nama }}</h2>
                <p class="text-sm mb-1">No. Induk: {{ $student->no_induk }}</p>
                <p class="text-sm mb-1">Telah menyelesaikan penyimakan Juz {{ $student->juz }}</p>

                @php
                    $nilai = $student->surats->avg('nilai');
                @endphp

                <p class="text-lg font-semibold mt-2">
                    Nilai Akhir: {{ $nilai ? number_format($nilai, 2) : '-' }}
                </p>

                <div class="mt-6 text-sm">
                    Jakarta, {{ now()->translatedFormat('d F Y') }}<br>
                    Kepala Sekolah <br><br>
                    <strong>Neor Imanah, M.Pd</strong><br>
                    NIP: 1234567890
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('certificates.index') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg shadow hover:bg-slate-700">
                <i class="bi bi-arrow-left mr-2"></i> Kembali
            </a>

            <a href="{{ route('certificates.downloadCertificate', $student->id) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                <i class="bi bi-cloud-arrow-down mr-2"></i> Download PDF
            </a>
        </div>
    </div>
@endsection
