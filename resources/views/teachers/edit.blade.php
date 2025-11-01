@extends('layouts.app')
@include('layouts.partials.swal')

@section('title', 'Edit Profile')

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

            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Edit Profile Guru</h1>

            {{-- Form Update --}}
            <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                {{--  <div>
                    <label for="name" class="block font-medium mb-1">Nama <span class="text-sm text-red-500">(nama ini tidak bisa di ubah!!!)</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}"
                        class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-800 cursor-not-allowed" disabled>
                </div>  --}}

                {{-- Email --}}
                <div>
                    <label for="email" class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Foto --}}
                <div>
                    <label for="photo" class="block font-medium mb-1">Foto Profile <span
                            class="text-sm text-red-500 font-semibold">(upload foto click di bawah ini!!!)</span></label>
                    <input type="file" name="photo" id="photo"
                        class="w-full text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">

                    {{-- Hidden input untuk hasil crop --}}
                    <input type="hidden" name="cropped_image" id="cropped_image">

                    @if ($teacher->photo)
                        <div class="flex justify-center space-x-4 mt-3">
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Foto"
                                class="w-20 h-20 rounded-full object-cover">
                        </div>
                    @else
                        <div class="flex justify-center mt-3">
                            <img src="{{ $teacher->get_gravatar_url() }}" alt="Foto"
                                class="w-20 h-20 rounded-full shadow">
                        </div>
                    @endif
                    <p class="text-center mt-5 text-lg font-semibold">{{ $teacher->name }}</p>
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

                <button type="submit" id="saveButtonTeacher"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </form>

            {{-- Form hapus foto --}}
            @if ($teacher->photo)
                <form action="{{ route('teacher.deletePhoto', $teacher->id) }}" method="POST"
                    onsubmit="return confirmDeletePhotoTeacher(this)" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        🗑️ Hapus Foto
                    </button>
                </form>
            @endif

            {{-- Form hapus profile --}}
            {{--  <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus profile ini? Data tidak bisa dikembalikan!')"
                class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    ❌ Hapus Profile
                </button>
            </form>  --}}
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
