@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-10">
        <div class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8">
            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">✏️ Edit Profile</h1>

            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('dashboard') }}"
                    class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    &larr; Kembali
                </a>
            </div>

            {{-- Menampilkan error validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Form Update --}}
            <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block font-medium mb-1">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}"
                        class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed" readonly>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Foto --}}
                <div>
                    <label for="photo" class="block font-medium mb-1">Foto Profile</label>
                    <input type="file" name="photo" id="photo" class="w-full text-sm">
                    @if ($teacher->photo)
                        <div class="flex items-center space-x-4 mt-3">
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Foto"
                                class="w-24 h-24 rounded-lg border object-cover shadow">
                        </div>
                    @else
                        <div class="flex justify-center mt-3">
                            <img src="{{ $teacher->get_gravatar_url() }}" alt="Foto"
                                class="w-20 h-20 rounded-full shadow">
                        </div>
                    @endif
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    💾 Simpan Perubahan
                </button>
            </form>

            {{-- Form hapus foto --}}
            @if ($teacher->photo)
                <form action="{{ route('teacher.deletePhoto', $teacher->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin hapus foto profil?')" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        🗑️ Hapus Foto
                    </button>
                </form>
            @endif

            {{-- Form hapus profile --}}
            <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus profile ini? Data tidak bisa dikembalikan!')"
                class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    ❌ Hapus Profile
                </button>
            </form>
        </div>
    </div>
@endsection
