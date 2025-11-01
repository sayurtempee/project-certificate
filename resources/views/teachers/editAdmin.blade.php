@extends('layouts.app')
@include('layouts.partials.swal')

@section('title', 'Edit Profile Admin')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-10">
        <div class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8">
            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('dashboard') }}"
                    class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    &larr; Kembali
                </a>
            </div>

            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Edit Profile Admin</h1>

            {{-- Form Update --}}
            <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                {{--  <div>
                    <label for="name" class="block font-medium mb-1">Nama
                        <span class="text-sm text-red-500 italic">(nama tidak bisa di ubah)</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" disabled
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 selection:text-red-500">
                </div>  --}}

                {{-- Email --}}
                <div>
                    <label for="email" class="block font-medium mb-1">Email
                        <span class="text-sm text-black-500 italic">(email bisa di ubah opsional)</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block font-medium mb-1">Password Baru
                        <span class="text-sm text-black-500 italic">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block font-medium mb-1">Konfirmasi Password
                        <span class="text-sm text-black-500 italic">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Foto --}}
                <div>
                    <label for="photo" class="block font-medium mb-1">Foto Profile
                        <span class="text-sm text-gre5-500 italic">(foto bisa di ubah opsional)</span>
                    </label>
                    <input type="file" name="photo" id="photo"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">

                    {{-- Hidden input untuk hasil crop --}}
                    <input type="hidden" name="cropped_image" id="cropped_image">

                    @if ($admin->photo)
                        <div class="flex justify-center space-x-4 mt-3">
                            <img src="{{ asset('storage/' . $admin->photo) }}" alt="Foto Profile"
                                class="w-20 h-20 rounded-full object-cover">
                        </div>
                    @else
                        <div class="flex justify-center mt-3">
                            <img src="{{ $admin->get_gravatar_url() }}" alt="Foto"
                                class="w-20 h-20 rounded-full shadow">
                        </div>
                    @endif
                    <p class="text-center mt-5 text-lg font-semibold">{{ $admin->name }}</p>
                </div>

                {{-- Modal Cropper --}}
                <div x-data="{ open: false }" x-on:open-cropper.window="open = true">
                    <!-- Overlay -->
                    <div x-show="open"
                        class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50" x-cloak>

                        <!-- Modal -->
                        <div class="bg-white p-5 rounded-lg shadow-lg max-w-lg w-full">
                            <h2 class="text-lg font-bold mb-3">✂️ Crop Foto</h2>

                            <!-- Preview area -->
                            <div class="w-full h-64 flex items-center justify-center border border-gray-200 rounded">
                                <img id="preview-photo" class="max-h-64 mx-auto">
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
                                    @click="open = false">
                                    Batal
                                </button>
                                <button type="button" id="crop-btn"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    @click="open = false">
                                    Crop & Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="saveButtonAdmin"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </form>

            {{-- Form Hapus Foto (pisah dari form update) --}}
            @if ($admin->photo)
                <form action="{{ route('admin.deletePhoto') }}" method="POST" class="mt-3 flex justify-end"
                    onsubmit="return confirm('Yakin hapus foto profil?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        🗑️ Hapus Foto
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const photoInput = document.getElementById('photo');
        const preview = document.getElementById('preview-photo');
        const croppedInput = document.getElementById('cropped_image');
        const cropBtn = document.getElementById('crop-btn');

        // Saat pilih foto
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;

                // Tunggu image muncul baru init cropper
                preview.onload = () => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(preview, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                    });

                    // Panggil event Alpine untuk buka modal
                    window.dispatchEvent(new CustomEvent('open-cropper'));
                };
            };
            reader.readAsDataURL(file);
        });

        // Saat klik Crop & Simpan
        cropBtn.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });
                croppedInput.value = canvas.toDataURL("image/png");
            }
        });
    </script>
@endpush
