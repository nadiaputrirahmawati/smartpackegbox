<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function uploadFoto(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'tracking_number' => 'required|string',
            // 'box' => 'required|string',
            'trigger_type' => 'required|string', // 'scan_input' atau 'paket_masuk'
        ]);

        // 2. Simpan Foto ke Storage
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $request->tracking_number . '.jpg';

            // Akan tersimpan di folder: storage/app/public/activity_logs
            $path = $file->storeAs('public/activity_logs', $filename);

            // 3. Simpan ke Database (Sesuaikan dengan nama Tabel/Model Anda)

            ActivityLog::create([
                'input_key' => $request->tracking_number,
                // 'box' => $request->box,
                'action_type' => ($request->trigger_type == 'scan_input') ? 'Mencoba input resi' : 'Paket berhasil masuk',
                'image_path' => $filename
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto berhasil disimpan!',
                'file' => $filename
            ], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 400);
    }
}
