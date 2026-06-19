<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function uploadLog(Request $request)
    {
        // Cari log berdasarkan log_id yang dikirim ESP32-CAM
        $log = \App\Models\ActivityLog::find($request->log_id);

        if ($log && $request->hasFile('foto')) {

            $file = $request->file('foto');

            // Ambil extension asli file
            $extension = $file->extension();

            // Generate nama file unik & readable
            $fileName = 'CAM_' . date('Ymd_His') . '_' . substr(uniqid(), -4) . '.' . $extension;

            // Simpan file ke storage/app/public/logs
            $path = $file->storeAs('logs', $fileName, 'public');

            // Simpan path ke database
            $log->update([
                'image_path' => $path
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto terunggah',
                'path' => $path
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Log tidak ditemukan atau foto kosong'
        ], 404);
    }
}
