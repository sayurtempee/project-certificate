<div class="w-64 bg-blue-900 text-white flex flex-col min-h-screen">
    <div class="p-4 text-lg font-bold">
        {{--  SDIA.13 <span class="text-yellow-400">.Rawamangun</span>  --}}
        <img src="{{ asset('img/logo_white.png') }}" alt="">
    </div>

    <nav class="flex-1 px-4 space-y-2">
        @auth
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('teacher.index') }}" class="block hover:text-yellow-300">Kelola Data Guru</a>
                <a href="{{ route('student.index') }}" class="block hover:text-yellow-300">Daftar Murid</a>
            @elseif (Auth::user()->role == 'teacher')
                <a href="{{ route('student.index') }}" class="block hover:text-yellow-300">Daftar Murid</a>
                <a href="{{ route('student.create') }}" class="block hover:text-yellow-300">Tambah Sertifikat</a>
                <a href="{{ route('students.download.img') }}" class="block hover:text-yellow-300">Sertifikat Download Image</a>
            @endif
        @endauth
    </nav>

    {{--  @auth
        @if (Auth::user()->role == 'admin')
            <form action="{{ route('logout') }}" method="POST" class="p-4">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg">
                    Logout
                </button>
            </form>
        @endif
    @endauth  --}}
</div>
