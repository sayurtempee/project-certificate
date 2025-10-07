<div class="w-52 bg-gradient-to-b from-blue-900 via-blue-800 to-blue-900 text-white flex flex-col min-h-screen">
    <div class="p-4 text-lg font-bold flex items-center justify-center">
        <img src="{{ asset('img/logo_white.png') }}" alt="" class="max-w-[140px]">
    </div>

    <nav class="flex-1 px-3 space-y-2 text-sm font-medium">
        @auth
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('teacher.index') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('teacher.*')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200' }}">
                    <i class="bi bi-people-fill text-lg"></i>
                    <span>Kelola Data Guru</span>
                </a>

                <a href="{{ route('student.index') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('student.*')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200 scale-[1.02]' }}">
                    <i class="bi bi-mortarboard-fill text-lg"></i>
                    <span>Daftar Murid</span>
                </a>

                <a href="{{ route('activity.logs.index') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('history.*')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200 scale-[1.02]' }}">
                    <i class="bi bi-clock-history text-lg"></i>
                    <span>History</span>
                </a>
            @elseif (Auth::user()->role == 'teacher')
                <a href="{{ route('student.index') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('student.index')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200 scale-[1.02]' }}">
                    <i class="bi bi-mortarboard-fill text-lg"></i>
                    <span>Daftar Murid</span>
                </a>

                <a href="{{ route('student.create') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('student.create')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200 scale-[1.02]' }}">
                    <i class="bi bi-plus-square-fill text-lg"></i>
                    <span>Tambah Sertifikat</span>
                </a>

                <a href="{{ route('certificates.index') }}"
                    class="flex items-center gap-2 w-full py-2.5 px-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('certificates.*')
                            ? 'bg-blue-600 text-yellow-300 shadow-md'
                            : 'hover:bg-blue-700 hover:text-yellow-200 scale-[1.02]' }}">
                    <i class="bi bi-card-image text-lg"></i>
                    <span>Sertifikat Image</span>
                </a>
            @endif
        @endauth
    </nav>
</div>
