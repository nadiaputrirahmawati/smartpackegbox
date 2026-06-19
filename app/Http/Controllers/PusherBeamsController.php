<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\PushNotifications\PushNotifications;

class PusherBeamsController extends Controller
{
    public function auth(Request $request)
    {
        // Pastikan user benar-benar sedang login
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Inisialisasi Pusher Beams SDK
        $beamsClient = new PushNotifications([
            "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID'),
            "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'), // Pastikan ini ada di .env Anda
        ]);

        // Generate token khusus untuk ID user tersebut (wajib dikonversi ke String)
        $beamsToken = $beamsClient->generateToken((string) $user->id);

        return response()->json($beamsToken);
    }
}