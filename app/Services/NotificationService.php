<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Pusher\PushNotifications\PushNotifications;

class NotificationService
{
    /**
     * Mengirim notifikasi langsung ke Pusher Beams
     */
    public function sendNotificationAsync($packageId = null, string $userId, string $name = null)
    {
        // Ambil base URL (contoh: http://127.0.0.1:8000)
        $baseUrl = config('app.url') ?? 'http://127.0.0.1:8000';

        try {
            // Inisialisasi SDK Pusher Beams
            $beamsClient = new PushNotifications([
                "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID'),
                "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'), 
            ]);

            // SUSUN DEEP LINK SEARAH DENGAN ROUTE: /packages/{id}/arrive
            // Kita gunakan $packageId untuk mengisi parameter {id}
            $deepLink = url("/user/packages/{$packageId}/arrive");

            // Kirim Push Notification ke User ID spesifik yang terdaftar di PWA
            $beamsClient->publishToUsers(
                [(string) $userId],
                [
                    "web" => [
                        "notification" => [
                            "title" => "Paket Tiba! 📦",
                            "body" => "Halo $name, SmartBox mendeteksi paket Anda telah diletakkan ke dalam kotak penyimpanan.",
                            "deep_link" => $deepLink, // <-- Menggunakan URL dinamis yang baru
                        ]
                    ]
                ]
            );

            Log::info("[Notification Service] Sukses mengirim notifikasi langsung ke User ID: " . $userId . " untuk Paket ID: " . $packageId);
            return true;

        } catch (\Exception $e) {
            Log::error("[Notification Service] Gagal mengirim notifikasi langsung: " . $e->getMessage());
            return false;
        }
    }

    public function sendDoorAlert($userId, $boxNumber)
    {
        $deepLink = url("/user/dashboard");
        
        try {
            $beamsClient = new PushNotifications([
                "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID'),
                "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'), 
            ]);

            $beamsClient->publishToUsers(
                [(string) $userId],
                [
                    "web" => [
                        "notification" => [
                            "title" => "⚠️ PERINGATAN: Pintu Terbuka!",
                            "body" => "Pintu SmartBox nomor {$boxNumber} terbuka terlalu lama. Segera tutup kembali demi keamanan paket Anda!",
                            "deep_link" => $deepLink, // Arahkan ke halaman utama dashboard
                        ]
                    ]
                ]
            );

            Log::warning("[Pusher Alert] Berhasil mengirim peringatan pintu terbuka ke User ID: " . $userId);
            return true;
        } catch (\Exception $e) {
            Log::error("[Pusher Alert] Gagal mengirim peringatan: " . $e->getMessage());
            return false;
        }
    }
}