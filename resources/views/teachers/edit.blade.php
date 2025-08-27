@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6 mt-6">
        <h1 class="text-2xl font-semibold mb-6">Edit Profile</h1>

        {{-- Tombol Kembali --}}
        <a href="{{ route('dashboard') }}"
            class="inline-block mb-4 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
            &larr; Kembali
        </a>

        {{-- Menampilkan error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block font-medium mb-1" for="name">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            {{-- Foto --}}
            <div class="mb-4">
                <label class="block font-medium mb-1" for="photo">Foto Profile</label>
                <input type="file" name="photo" id="photo" class="w-full">
                @if ($teacher->photo)
                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Foto"
                        class="w-24 h-24 mt-2 rounded border object-cover">
                @endif
            </div>

            <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
