<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Admin/Profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone_number' => 'nullable|max:14',
            'password' => 'nullable|string|min:8', // Opsional, hanya jika ingin ganti password
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // 1. Update Data Dasar
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->phone_number = $validated['phone_number'];

        // 2. Update Password (Hanya jika kolom password diisi)
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // 3. Update Avatar (Jika ada file gambar yang diunggah)
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan yang baru
            $path = $request->file('avatar')->store('avatars', 'public');
            // Simpan URL lengkap (sesuaikan dengan setup storage url Anda)
            $user->avatar = '/storage/' . $path; 
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            // 'current_password' otomatis mengecek kecocokan dengan password user saat ini di database
            'current_password' => ['required', 'current_password'], 
            // 'confirmed' otomatis mencari input 'password_confirmation' dan mencocokkannya
            'password' => ['required', 'confirmed', 'min:8'], 
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password akun Anda berhasil diperbarui.');
    }
}
