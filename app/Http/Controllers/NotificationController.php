<?php

namespace App\Http\Controllers;

use Pusher\PushNotifications\PushNotifications;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function send(string $userId)
    {
        try {
            // Inisialisasi SDK Pusher Beams
            $beamsClient = new PushNotifications([
                "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID', 'e49f734a-45b3-454e-baf4-a83f348027f8'),
                "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'), // Pastikan sudah di-set di file .env Anda
            ]);

            // Kirim Push Notification ke User ID spesifik yang terdaftar di PWA
            $beamsClient->publishToUsers(
                [(string) $userId],
                [
                    "web" => [
                        "notification" => [
                            "title" => "Paket Tiba! 📦",
                            "body" => "SmartBox mendeteksi paket Anda telah diletakkan ke dalam kotak penyimpanan.",
                            "deep_link" => url('/dashboard'),
                        ]
                    ]
                ]
            );

            Log::info("[Pusher Controller] Sukses mengirim notifikasi ke User ID: " . $userId);
            return response()->json(['status' => 'success', 'message' => 'Notification sent']);

        } catch (\Exception $e) {
            Log::error("[Pusher Controller] Gagal mengirim notifikasi: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}