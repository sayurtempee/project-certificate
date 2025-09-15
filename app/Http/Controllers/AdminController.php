<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function editAdminProfile()
    {
        $admin = Auth::user();

        if ($admin->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('teachers.editAdmin', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        if ($admin->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate(
            [
                'email'          => 'nullable|email|unique:users,email,' . $admin->id,
                'password'       => 'nullable|string|min:6|confirmed',
                'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'cropped_image'  => 'nullable|string', // hasil crop (base64)
                'delete_photo'   => 'nullable|boolean', // tombol hapus
            ],
            [
                'email.unique'        => 'Email sudah digunakan oleh pengguna lain.',
                'password.confirmed'  => 'Konfirmasi password tidak cocok.',
                'password.min'        => 'Password minimal 6 karakter.'
            ]
        );

        // ---- Hapus foto ----
        if ($request->has('delete_photo') && $request->delete_photo == 1) {
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
                $admin->photo = null;
            }
        }

        // ---- Crop foto dari Cropper.js (base64) ----
        if ($request->filled('cropped_image')) {
            $imageData = $request->cropped_image;
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'admin_photos/' . uniqid() . '.png';

            Storage::disk('public')->put($imageName, base64_decode($image));

            // Hapus foto lama
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }

            $admin->photo = $imageName;
        }
        // ---- Upload foto biasa tanpa crop ----
        elseif ($request->hasFile('photo')) {
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }

            $photoPath = $request->file('photo')->store('admin_photos', 'public');
            $admin->photo = $photoPath;
        }

        // Update email
        if (!empty($validated['email'])) {
            $admin->email = $validated['email'];
        }

        // Update password
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $admin = Auth::user();

        if ($admin->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if ($admin->photo) {
            Storage::disk('public')->delete($admin->photo);
            $admin->photo = null;
            $admin->save();
        }

        return redirect()->route('admin.edit')->with('success', 'Foto profil berhasil dihapus.');
    }
}
