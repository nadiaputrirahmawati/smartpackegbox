<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // 1. Hitung paket yang sedang "In Transit" (Status = waiting)
        $activePackagesCount = Package::where('user_id', $userId)
            ->where('package_status', 'waiting')
            ->count();

        // 2. Ambil 5 riwayat paket terbaru untuk tabel
        $recentDeliveries = Package::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
            

        return Inertia::render('Users/Dashboard', [
            'activePackagesCount' => $activePackagesCount,
            'recentDeliveries' => $recentDeliveries,
            // Opsional: Kirim ID Letter Box user untuk ditampilkan
            'letterBoxId' => $request->user()->letter_box ?? 'Unassigned',
            'userId' => $userId,
        ]);
    }

    public function openUserDoor(Request $request)
{
    $user = $request->user();
    $action = $request->input('action'); // 'open' atau 'close'

    if (!$user->letter_box) {
        return response()->json(['message' => 'Identitas Box tidak ditemukan.'], 400);
    }

    // Catat log
    ActivityLog::create([
        'user_id' => $user->id,
        'actor_type' => 'user',
        'action_type' => 'door_' . $action,
    ]);

    // Payload tetap sama, ESP32 yang akan mengirim balik status "waiting"
    $payload = json_encode([
        'action' => $user->letter_box . "_USER_" . strtoupper($action),
    ]);

    $mqtt = new MqttService();
    $mqtt->publish('smartbox/box/command', $payload);

    return response()->json(['message' => 'Perintah terkirim, menunggu respon ESP32...']);
}

    // public function openUserDoor(Request $request)
    // {
    //     $user = $request->user();
    //     $action = $request->input('action'); // 'open' atau 'close'

    //     if (!$user->letter_box) {
    //         return response()->json(['message' => 'Identitas Box tidak ditemukan.'], 400);
    //     }

    //     ActivityLog::create([
    //         'user_id' => $user->id,
    //         'actor_type' => 'user',
    //         'action_type' => 'door_' . $action,
    //     ]);

    //     // Kita buat payloadnya spesifik: IDBOX_USER_OPEN atau IDBOX_USER_CLOSE
    //     $payload = json_encode([
    //         'action' => $user->letter_box . "_USER_" . strtoupper($action),
    //     ]);

    //     $mqtt = new MqttService();
    //     $mqtt->publish('smartbox/box/command', $payload);

    //     return response()->json(['message' => 'Perintah terkirim, menunggu respon ESP32...']);
    // }
}
