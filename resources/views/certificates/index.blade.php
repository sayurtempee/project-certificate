@extends('layouts.dashboard')

@section('title', 'Sertifikat Download Image')

@section('dashboard-content')
    @include('layouts.partials.swal')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-10 text-center sm:text-left">
            <h3
                class="text-3xl sm:text-4xl font-extrabold text-slate-800 mb-3 flex items-center justify-center sm:justify-start gap-3">
                <i class="bi bi-cloud-arrow-down text-blue-600 text-xl"></i>
                <span>Download Sertifikat (Landscape)</span>
            </h3>
            <p class="text-slate-600 max-w-2xl mx-auto sm:mx-0 leading-relaxed">
                Kelola dan <em>unduh</em> sertifikat murid dengan mudah. Perbarui data seperti tanggal dan tempat kelulusan,
                nama kepala sekolah, serta NIP dengan cepat dan efisien.
            </p>
        </div>

        @if ($students->count())
            <div
                class="bg-gradient-to-br from-white via-slate-50 to-blue-50 shadow-xl rounded-2xl overflow-hidden border border-slate-200">
                <!-- Header Card -->
                <div
                    class="px-6 py-4 bg-gradient-to-r from-blue-100 to-indigo-100 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <h4 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <i class="bi bi-table text-blue-600"></i>
                        Daftar Siswa <span class="text-slate-500">({{ $students->count() }})</span>
                    </h4>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">#</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">Nama</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">No Induk</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">Juz</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">Tanggal Lulus</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">Tempat</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">Nama Kepsek</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold">NIP Kepsek</th>
                                <th class="px-4 py-3 text-xs sm:text-sm font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @foreach ($students as $index => $student)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-4 py-3 text-slate-700 font-semibold">{{ $index + 1 }}</td>

                                    <!-- Nama -->
                                    <td class="px-4 py-3 font-medium text-slate-800 min-w-[200px]">{{ $student->nama }}</td>

                                    <!-- No Induk -->
                                    <td class="px-4 py-3 text-slate-600 min-w-[110px]">{{ $student->no_induk }}</td>

                                    <!-- Juz -->
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 text-zs font-small">
                                            Juz {{ $student->juz }}
                                        </span>
                                    </td>

                                    <!-- Tanggal Lulus -->
                                    <td class="px-4 py-3 space-y-2">
                                        <form action="{{ route('certificates.updateTanggalLulus', $student->id) }}"
                                            method="POST"
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                            @csrf
                                            <input type="date" name="tanggal_lulus"
                                                value="{{ $student->tanggal_lulus ? $student->tanggal_lulus->format('Y-m-d') : '' }}"
                                                class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 w-full sm:w-auto">
                                            <button type="submit"
                                                class="w-full sm:w-auto h-10 bg-yellow-500 hover:bg-yellow-600 text-white px-3 rounded-lg text-sm font-medium flex items-center justify-center transition">
                                                <i class="bi bi-save mr-1"></i> Simpan
                                            </button>
                                        </form>
                                        <p class="text-xs text-slate-500">
                                            Saat ini:
                                            <span class="font-semibold">
                                                {{ $student->tanggal_lulus ? $student->tanggal_lulus->translatedFormat('d F Y') : 'Belum Ditetapkan' }}
                                            </span>
                                        </p>
                                    </td>

                                    <!-- Tempat Kelulusan -->
                                    <td class="px-4 py-3">
                                        <form action="{{ route('certificates.updateTempatKelulusan', $student->id) }}"
                                            method="POST"
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                            @csrf
                                            <input type="text" name="tempat_kelulusan"
                                                value="{{ $student->tempat_kelulusan ?? '' }}" placeholder="Contoh: Jakarta"
                                                class="px-3 py-2 border border-slate-300 rounded-lg text-sm w-full sm:w-auto focus:ring-2 focus:ring-blue-400">
                                            <button type="submit"
                                                class="w-full sm:w-auto h-10 bg-blue-500 hover:bg-blue-600 text-white px-3 rounded-lg text-sm font-medium flex items-center justify-center transition">
                                                <i class="bi bi-save mr-1"></i> Simpan
                                            </button>
                                        </form>
                                        <p class="text-sd text-slate-500">
                                            Saat ini:
                                            <span class="font-semibold">
                                                {{ $student->tempat_kelulusan ? $student->tempat_kelulusan : 'Belum Ditetapkan' }}
                                            </span>
                                        </p>
                                    </td>

                                    <!-- Nama Kepsek -->
                                    <td class="px-4 py-3">
                                        <form action="{{ route('certificates.updateNamaKepsek', $student->id) }}"
                                            method="POST"
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                            @csrf
                                            <input type="text" name="nm_kepsek" value="{{ $student->nm_kepsek ?? '' }}"
                                                placeholder="Nama Kepala Sekolah"
                                                class="px-3 py-2 border border-slate-300 rounded-lg text-sm w-full sm:w-auto focus:ring-2 focus:ring-blue-400">
                                            <button type="submit"
                                                class="w-full sm:w-auto h-10 bg-indigo-500 hover:bg-indigo-600 text-white px-3 rounded-lg text-sm font-medium flex items-center justify-center transition">
                                                <i class="bi bi-save mr-1"></i> Simpan
                                            </button>
                                        </form>
                                        <p class="text-xs text-slate-500">
                                            Saat ini:
                                            <span class="font-semibold">
                                                {{ $student->nm_kepsek ? $student->nm_kepsek : 'Nama Belum Ditetapkan' }}
                                            </span>
                                        </p>
                                    </td>

                                    <!-- NIP Kepsek -->
                                    <td class="px-4 py-3">
                                        <form action="{{ route('certificates.updateNipKepsek', $student->id) }}"
                                            method="POST"
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                            @csrf
                                            <input type="text" name="nip_kepsek"
                                                value="{{ $student->nip_kepsek ?? '' }}" placeholder="NIP Kepala Sekolah"
                                                class="px-3 py-2 border border-slate-300 rounded-lg text-sm w-36 sm:w-auto focus:ring-2 focus:ring-blue-400">
                                            <button type="submit"
                                                class="w-full sm:w-auto h-10 bg-teal-500 hover:bg-teal-600 text-white px-3 rounded-lg text-sm font-medium flex items-center justify-center transition">
                                                <i class="bi bi-save mr-1"></i> Simpan
                                            </button>
                                        </form>
                                        <p class="text-xs text-slate-500">
                                            Saat ini:
                                            <span class="font-semibold">
                                                {{ $student->nip_kepsek ? $student->nip_kepsek : 'NIP Belum Ditetapkan' }}
                                            </span>
                                        </p>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('certificates.showCertificate', $student->id) }}"
                                                class="inline-flex items-center justify-center w-28 h-10 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 transition">
                                                <i class="bi bi-eye flex-shrink-0 mr-1"></i>
                                                <span class="leading-none">Lihat</span>
                                            </a>
                                            <a href="{{ route('certificates.downloadCertificate', $student->id) }}"
                                                class="inline-flex items-center justify-center w-28 h-10 bg-green-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-green-600 transition">
                                                <i class="bi bi-cloud-arrow-down flex-shrink-0 mr-1"></i>
                                                <span class="leading-none">Download</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if (isset($students) && method_exists($students, 'links'))
                    <div class="px-6 py-4 bg-white border-t border-slate-200 flex justify-center">
                        {!! $students->links() !!}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div
                class="text-center py-16 bg-gradient-to-br from-white to-slate-50 shadow-md rounded-2xl border border-slate-200">
                <div class="flex flex-col items-center gap-4">
                    <div class="p-5 bg-slate-100 rounded-full">
                        <i class="bi bi-inbox text-4xl text-slate-400"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-bold text-slate-800">Belum Ada Data Siswa</h4>
                    <p class="text-slate-600 max-w-md">Tidak ada data siswa untuk sertifikat saat ini. Tambahkan data siswa
                        terlebih dahulu untuk mulai membuat sertifikat.</p>
                    <a href="{{ route('student.index') }}"
                        class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
                        <i class="bi bi-plus-circle mr-2"></i> Tambah Siswa
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
