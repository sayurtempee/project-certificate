<div class="w-52 bg-gradient-to-b from-blue-900 via-blue-800 to-blue-900 text-white flex flex-col min-h-screen">
    <div class="p-4 text-lg font-bold flex items-center justify-center">
        <img src="{{ asset('img/logo_white.png') }}" alt="" class="max-w-[140px]">
    </div>

    <nav class="flex-1 px-3 space-y-2 text-sm font-medium">
        @auth
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('teacher.index') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                   {{ request()->routeIs('teacher.*')
                       ? 'bg-blue-600 text-yellow-300 shadow-md'
                       : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-people-fill text-lg"></i>
                    Kelola Data Guru
                </a>
                <a href="{{ route('student.index') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                   {{ request()->routeIs('student.*')
                       ? 'bg-blue-600 text-yellow-300 shadow-md'
                       : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-mortarboard-fill text-lg"></i>
                    Daftar Murid
                </a>
                <a href="{{ route('activity.logs.index') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                    {{ request()->routeIs('history.*')
                        ? 'bg-blue-600 text-yellow-300 shadow-md'
                        : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-clock-history text-lg"></i> History </a>
            @elseif (Auth::user()->role == 'teacher')
                <a href="{{ route('student.index') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                   {{ request()->routeIs('student.index')
                       ? 'bg-blue-600 text-yellow-300 shadow-md'
                       : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-mortarboard-fill text-lg"></i>
                    Daftar Murid
                </a>
                <a href="{{ route('student.create') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                   {{ request()->routeIs('student.create')
                       ? 'bg-blue-600 text-yellow-300 shadow-md'
                       : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-plus-square-fill text-lg"></i>
                    Tambah Sertifikat
                </a>
                <a href="{{ route('certificates.index') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg transition
                   {{ request()->routeIs('students.certificate.*')
                       ? 'bg-blue-600 text-yellow-300 shadow-md'
                       : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-card-image text-lg"></i>
                    Sertifikat Image
                </a>
            @endif
        @endauth
    </nav>
</div>
