<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU ATAS
        $stats = [
            'total_packages_today' => Package::whereDate('created_at', today())->count(),
            'active_users' => User::where('role', 'user')->where('status', 'active')->count(),
            'failed_access_today' => ActivityLog::whereDate('created_at', today())
                ->where(function ($query) {
                    $query->where('status', 'failed')
                        ->orWhere('event', 'access_denied');
                })->count(),
        ];

        // 2. STATUS LOKER (SLOT A & SLOT B)
        $slots = [
            $this->getSlotStatus('A', 'Slot 01'),
            $this->getSlotStatus('B', 'Slot 02'),
        ];

        // 3. RIWAYAT TERBARU (5 Log Terakhir) - Cukup fetch data mentah + sedikit manipulasi relasi
        $recent_logs = ActivityLog::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'event' => $log->event,
                    'method' => $log->method,
                    'status' => $log->status,
                    'input_value' => $log->input_value,
                    'actor_type' => $log->actor_type,
                    // Tetapkan nama aktor langsung dari relasi (jika ada)
                    'actor_name' => $log->user ? $log->user->name : ucfirst($log->actor_type),
                    'created_at' => $log->created_at->format('d M Y, H:i:s'),
                    // Konversi path gambar ke URL publik
                    'image_url' => $log->image_path ? Storage::url($log->image_path) : null,
                ];
            });

        return Inertia::render('Admin/Index', [
            'stats' => $stats,
            'slots' => $slots,
            'recent_logs' => $recent_logs,
        ]);
    }

    /**
     * Helper: Cek status loker berdasarkan kepemilikan dan paket
     */
    private function getSlotStatus($boxLetter, $slotName)
    {
        $user = User::where('letter_box', $boxLetter)->first();

        $package = null;
        if ($user) {
            $package = Package::where('user_id', $user->id)
                ->where('package_status', 'arrived')
                ->latest()
                ->first();
        }

        return [
            'name' => $slotName,
            'box' => $boxLetter,
            'is_occupied' => $package ? true : false,
            'package_id' => $package ? $package->tracking_number : null,
            'has_owner' => $user ? true : false,
            'owner_name' => $user ? $user->name : 'Belum Ditetapkan',
            'owner_avatar' => $user && $user->avatar ? Storage::url($user->avatar) : null,
        ];
    }
}
