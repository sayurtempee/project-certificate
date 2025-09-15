@extends('layouts.dashboard')

@section('dashboard-content')
    <div x-data="{ openCreate: false, openEdit: false, editData: {} }">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-900">Kelola Guru</h2>
            <button @click="openCreate = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                <i class="bi bi-plus-circle-dotted"></i> Tambah Guru
            </button>
        </div>

        @include('layouts.partials.swal')

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 mb-3 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{--  End Message  --}}

        <!-- Table daftar guru -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="p-3 border text-center">#</th>
                        <th class="p-3 border text-center">Foto</th>
                        <th class="p-3 border text-center">Nama</th>
                        <th class="p-3 border text-center">Email</th>
                        <th class="p-3 border text-center">Status</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr class="hover:bg-blue-50">
                            <td class="p-3 border text-center">{{ $loop->iteration }}</td>
                            <td class="p-3 border text-center align-middle">
                                <div class="flex justify-center items-center h-full">
                                    @if ($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Foto"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-700 text-white font-bold border-2 border-blue-700 uppercase text-sm leading-none">
                                            {{ $teacher->getInitials() }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3 border text-center">{{ $teacher->name }}</td>
                            <td class="p-3 border text-center">{{ $teacher->email }}</td>
                            <td class="p-3 border text-center">
                                @if ($teacher->is_online)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                        <i class="bi bi-circle-fill text-green-500 mr-1"></i>
                                        Online
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm font-medium">
                                        <i class="bi bi-circle-fill text-red-400 mr-1"></i>
                                        Offline
                                        <span class="ml-1 text-xs text-red-500 italic">
                                            ({{ $teacher->last_seen ? $teacher->last_seen->diffForHumans() : 'Belum login' }})
                                        </span>
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border text-center">
                                <!-- Tombol Edit -->
                                <button
                                    @click="openEdit = true; editData = {
                                        id: '{{ $teacher->id }}',
                                        name: '{{ $teacher->name }}',
                                        email: '{{ $teacher->email }}'
                                    }"
                                    class="text-blue-600 hover:text-blue-800 mx-1" title="Edit">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin mau hapus guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 mx-1" title="Hapus">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Create -->
        <div x-show="openCreate" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-opacity-50 backdrop-blur-lg z-50">
            <div @click.away="openCreate = false" x-transition.opacity.scale.duration.200ms
                class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">

                <h3 class="text-xl font-bold mb-4">Tambah Guru</h3>
                <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-semibold">Nama</label>
                        <input type="text" name="name" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold">Email</label>
                        <input type="email" name="email" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold">Password</label>
                        <input type="password" name="password" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold">Foto</label>
                        <input type="file" name="photo" class="w-full border rounded-lg p-2">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openCreate = false"
                            class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="openEdit" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-opacity-50 backdrop-blur-lg z-50">
            <div @click.away="openEdit = false" x-transition.opacity.scale.duration.200ms
                class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">

                <h3 class="text-xl font-bold mb-4">Edit Guru</h3>
                <form :action="`/teacher/${editData.id}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-semibold">Nama</label>
                        <input type="text" name="name" x-model="editData.name" class="w-full border rounded-lg p-2"
                            required>
                    </div>

                    <div>
                        <label class="block font-semibold">Email</label>
                        <input type="email" name="email" x-model="editData.email" class="w-full border rounded-lg p-2"
                            required>
                    </div>

                    <div>
                        <label class="block font-semibold">Password <span
                                class="text-sm text-gray-500">(opsional)</span></label>
                        <input type="password" name="password" class="w-full border rounded-lg p-2">
                    </div>

                    <div>
                        <label class="block font-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2">
                    </div>

                    <div>
                        <label class="block font-semibold">Foto Baru</label>
                        <input type="file" name="photo" class="w-full border rounded-lg p-2">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
