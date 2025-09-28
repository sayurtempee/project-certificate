@extends('layouts.dashboard')

@section('title', 'Sertifikat Download Image')

@section('dashboard-content')
    @include('layouts.partials.swal')

    <div class="container mx-auto px-5 py-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="bi bi-cloud-arrow-down"></i>
            Download Sertifikat Image (LANDSCAPE)
        </h3>

        @if ($students->count())
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-4 py-3 border-b font-semibold">#</th>
                            <th class="px-4 py-3 border-b font-semibold">Nama</th>
                            <th class="px-4 py-3 border-b font-semibold">No Induk</th>
                            <th class="px-4 py-3 border-b font-semibold">Juz</th>
                            <th class="px-4 py-3 border-b font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 border-b font-medium text-slate-700">{{ $student->nama }}</td>
                                <td class="px-4 py-3 border-b">{{ $student->no_induk }}</td>
                                <td class="px-4 py-3 border-b">{{ $student->juz }}</td>
                                <td class="px-4 py-3 border-b space-x-2">
                                    <a href="{{ route('certificates.showCertificate', $student->id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700">
                                        <i class="bi bi-eye mr-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('certificates.downloadCertificate', $student->id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-lg shadow hover:bg-green-700">
                                        <i class="bi bi-cloud-arrow-down mr-1"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-slate-600">Belum ada data siswa untuk sertifikat.</p>
        @endif
    </div>
@endsection
