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
            <div class="absolute inset-0 text-black px-12 py-16 flex flex-col justify-between">

                <!-- Bagian tengah (judul + nama + nilai) -->
                <div class="text-center mt-6">
                    <h1 class="text-3xl font-bold mt-8">SERTIFIKAT</h1>
                    <p class="text-sm mb-1">Diberikan kepada</p>
                    <h2 class="text-2xl font-bold mb-1 underline">{{ $student->nama }}</h2>
                    <p class="text-sm mb-1 font-semibold">No. Induk: {{ $student->no_induk }}</p>
                    <p class="text-sm mb-1">Telah menyelesaikan penyimakan <span class="font-semibold">Juz {{ $student->juz }}</span></p>

                    @php
                        $nilai = $student->surats->avg('nilai');
                    @endphp
                    <p class="text-lg font-bold mt-3">
                        Nilai Akhir: {{ $nilai ? number_format($nilai, 0) : '-' }}
                    </p>
                </div>

                <!-- Bagian tanda tangan -->
                <div class="text-center mt-6">
                    <span>{{ $certificate['tempat'] }}, {{ $student->tanggal_lulus->translatedFormat('d-M-Y') }}</span>
                    <br>
                    Kepala Sekolah
                    <div class="mt-24">
                        <strong class="block">{{ $certificate['nama_kepala_sekolah'] }}</strong>
                        <span class="block">NIP: {{ $certificate['nip'] }}</span>
                    </div>
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
