@extends('layouts.dashboard')

@section('title', 'Sertifikat Download Image')

@section('dashboard-content')
    @include('layouts.partials.swal')

    <div class="container mx-auto px-5 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-full">
                    <i class="bi bi-cloud-arrow-down text-blue-600 text-xl"></i>
                </div>
                Download Sertifikat Image (LANDSCAPE)
            </h3>
            <p class="text-slate-600">Kelola dan unduh sertifikat siswa dengan mudah. Update tanggal lulus dan tempat
                kelulusan di sini.</p>
        </div>

        @if ($students->count())
            <!-- Table Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-slate-200">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200">
                    <h4 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <i class="bi bi-table text-blue-600"></i>
                        Daftar Siswa ({!! $students->count() !!})
                    </h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">#</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">Nama</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">No Induk</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">Juz</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">Tanggal
                                    Kelulusan</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">Tempat
                                    Kelulusan</th>
                                <th class="px-6 py-4 border-b border-slate-200 font-semibold text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $student->nama }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $student->no_induk }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Juz {{ $student->juz }}
                                        </span>
                                    </td>

                                    <!-- Form update tanggal_lulus -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="text-sm text-slate-500 mb-2">
                                                <i class="bi bi-calendar-check mr-1"></i>
                                                Tanggal Lulus (Update di bawah):
                                            </div>
                                            <form action="{{ route('certificates.updateTanggalLulus', $student->id) }}"
                                                method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="date" name="tanggal_lulus"
                                                    id="tanggal_lulus_{{ $student->id }}"
                                                    value="{{ $student->tanggal_lulus ? $student->tanggal_lulus->format('Y-m-d') : '' }}"
                                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-200">
                                                    <i class="bi bi-save mr-1"></i> Simpan
                                                </button>
                                            </form>
                                            @if ($student->tanggal_lulus)
                                                <div class="text-xs text-slate-500 mt-1">
                                                    Saat ini: {{ $student->tanggal_lulus->translatedFormat('d F Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <form action="{{ route('certificates.updateTempatKelulusan', $student->id) }}"
                                            method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="text" name="tempat_kelulusan"
                                                id="tempat_kelulusan_{{ $student->id }}"
                                                value="{{ $student->tempat_kelulusan ?? '' }}"
                                                placeholder="Contoh: Jakarta"
                                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                                <i class="bi bi-save mr-1"></i> Simpan
                                            </button>
                                        </form>
                                        @if ($student->tempat_kelulusan)
                                            <div class="text-xs text-slate-500 mt-1">
                                                Saat ini: {{ $student->tempat_kelulusan }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Tombol aksi -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('certificates.showCertificate', $student->id) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                                <i class="bi bi-eye mr-1"></i> Lihat
                                            </a>
                                            <a href="{{ route('certificates.downloadCertificate', $student->id) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                                                <i class="bi bi-cloud-arrow-down mr-1"></i> Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination if needed (assuming you have it in the controller) -->
            @if (isset($students) && method_exists($students, 'links'))
                <div class="mt-6 flex justify-center">
                    {!! $students->links() !!}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12 bg-white shadow-lg rounded-xl border border-slate-200">
                <div class="flex flex-col items-center gap-4">
                    <div class="p-4 bg-slate-100 rounded-full">
                        <i class="bi bi-inbox text-4xl text-slate-400"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-slate-800">Belum Ada Data Siswa</h4>
                    <p class="text-slate-600 max-w-md">Tidak ada siswa yang tersedia untuk sertifikat saat ini. Silakan
                        tambahkan data siswa terlebih dahulu.</p>
                    <a href="{{ route('students.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-medium rounded-lg shadow-sm hover:bg-blue-600 transition-all duration-200">
                        <i class="bi bi-plus-circle mr-2"></i> Tambah Siswa
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
