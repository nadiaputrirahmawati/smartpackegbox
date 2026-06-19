<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Mengambil log aktivitas dari database berdasarkan schema Anda
        $rawActivities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(5) // Ambil 5 aktivitas terakhir
            ->get();

        // Memformat data untuk frontend
        $activities = $rawActivities->map(function ($log) {
            $title = '';
            $desc = '';
            $icon = 'Shield';

            // Menentukan teks & ikon berdasarkan action_type database Anda
            if ($log->action_type === 'scan_barcode') {
                $title = 'Paket Diterima';
                $desc = "Resi #{$log->input_key} berhasil masuk ke kompartemen.";
                $icon = 'Package';
            } elseif ($log->action_type === 'open_door') {
                $title = 'Akses Boks';
                $statusText = $log->status === 'success' ? 'Berhasil dibuka.' : 'Gagal dibuka.';
                $desc = "Kunci digital digunakan. {$statusText}";
                $icon = 'Key';
            } else {
                $title = 'Aktivitas Sistem';
                $desc = "Tindakan: {$log->action_type}.";
                $icon = 'ShieldCheck';
            }

            // Memformat waktu sesuai desain ("Today, 10:42 AM")
            $date = Carbon::parse($log->created_at);
            if ($date->isToday()) {
                $timeFormatted = 'Hari ini, ' . $date->format('H:i');
            } elseif ($date->isYesterday()) {
                $timeFormatted = 'Kemarin, ' . $date->format('H:i');
            } else {
                $timeFormatted = $date->format('d M Y, H:i');
            }

            return [
                'id' => $log->id,
                'title' => $title,
                'desc' => $desc,
                'time' => $timeFormatted,
                'icon' => $icon,
            ];
        });

        return Inertia::render('Users/UserProfile', [
            'user' => $user,
            'activities' => $activities
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->user()->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone_number' => 'nullable|string|max:20',
        ]);

        $request->user()->update($request->only('name', 'username', 'email', 'phone_number'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            // Validasi: harus berupa gambar, format tertentu, maksimal 2MB
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada (agar server tidak penuh)
            if ($user->avatar) {
                // Menghapus string '/storage/' dari awal path untuk mendapatkan path aslinya
                $oldPath = str_replace('/storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Simpan foto baru ke folder 'avatars' di storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');

            // Simpan path yang bisa diakses publik ke database
            $user->update(['avatar' => '/storage/' . $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
