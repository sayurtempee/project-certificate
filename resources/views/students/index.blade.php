@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container my-5">
        <h3 class="mb-4 text-2xl font-semibold">Daftar Siswa Juz {{ $juz ?? 'Semua' }}</h3>

        {{-- Form filter --}}
        <form method="GET" action="{{ route('student.index') }}" class="mb-4 bg-white p-4 rounded-lg shadow-md">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama siswa...">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="juz" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Juz</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ $juz == $i ? 'selected' : '' }}>
                                Juz {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        @if (Auth::user()->role == 'teacher')
            <form action="{{ route('student.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <label for="csvFileInput"
                    class="btn btn-success px-4 py-2 rounded-lg shadow hover:bg-green-600 cursor-pointer">
                    <i class="bi bi-upload me-2"></i> Upload CSV
                </label>
                <input type="file" name="file" accept=".csv" class="d-none" id="csvFileInput" required>
                <button type="submit" class="d-none" id="csvSubmitBtn"></button>
            </form>
        @endif

        {{-- Table siswa --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>No Induk</th>
                        <th>Penyimak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $s)
                        <tr>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->no_induk }}</td>
                            <td>{{ $s->penyimak }}</td>
                            <td>
                                <a href="{{ route('student.show', $s->id) }}" class="btn btn-sm btn-primary">
                                    Detail Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data siswa untuk Juz ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('csvFileInput');
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                this.form.submit();
            }
        });
    </script>
@endsection
