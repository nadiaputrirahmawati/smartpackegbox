<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Tangkap parameter filter dari Vue
        $filter = $request->input('filter', 'semua');
        $date = $request->input('date');

        // 1. Query Dasar
        $query = ActivityLog::with('package')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('package', function ($sub) use ($user) {
                        $sub->where('user_id', $user->id);
                    });
            })
            ->where('event', '!=', 'door_closed');

        // 2. Terapkan Filter Kategori (Event)
        if ($filter === 'paket_masuk') {
            $query->where('event', 'package_arrived');
        } elseif ($filter === 'akses_buka') {
            $query->where('event', 'door_opened');
        }

        // 3. Terapkan Filter Tanggal
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // 4. Pagination dengan withQueryString() agar filter menempel di URL
        $logs = $query->latest()->paginate(10)->withQueryString()->through(function ($log) {
            $display = $this->formatDisplay($log);
            return [
                'id' => $log->id,
                'timestamp' => $log->created_at->format('d M Y, H:i:s') . ' WIB',
                'badge' => $display['badge'],
                'title' => $display['title'],
                'subtitle' => $display['subtitle'],
                'actor' => $display['actor'],
                'method' => $display['method'],
                'image_url' => $log->image_path ? Storage::url($log->image_path) : null,
                'status' => $log->status,
                'is_error' => ($log->status === 'failed' || $log->event === 'access_denied')
            ];
        });

        // 5. Kirim data logs dan filter aktif kembali ke Vue
        return Inertia::render('Users/ActivityLog', [
            'logs' => $logs,
            'filters' => [
                'filter' => $filter,
                'date' => $date,
            ]
        ]);
    }

    /**
     * Helper untuk memformat teks dan gaya sesuai desain UI baru
     */
    private function formatDisplay($log)
    {
        $isFailed = $log->status === 'failed' || $log->event === 'access_denied';

        if ($isFailed) {
            return [
                'badge' => ['text' => 'AKSES DITOLAK', 'class' => 'bg-[#fce8e8] text-[#d93a3a]'],
                'title' => 'Percobaan Akses Gagal',
                'subtitle' => 'Data Input: <span class="font-mono text-[#d93a3a]">' . ($log->input_value ?? 'Kosong') . '</span> (Gagal)',
                'actor' => 'Tidak Dikenal',
                'method' => 'Alert: Lockout / Foto Diambil'
            ];
        }

        return match ($log->event) {
            'package_arrived' => [
                'badge' => ['text' => 'PAKET TELAH TIBA', 'class' => 'bg-[#111840] text-[#E9F1FF]'],
                'title' => 'Paket Sudah Berada didalam Kotak',
                'subtitle' => 'Nomor Resi: <span class="font-mono text-[#577A9F]">' . ($log->package ? $log->package->tracking_number : $log->input_value) . '</span>',
                'actor' => 'Kurir',
                'method' => 'Metode: ' . strtoupper($log->method)
            ],
            'door_opened' => [
                'badge' => ['text' => 'PINTU DIBUKA', 'class' => 'bg-[#e0f2fe] text-[#0369a1]'],
                'title' => $log->actor_type === 'user' ? 'Akses Pengguna Utama' : 'Kurir Membuka Pintu',
                // Sensor PIN 4 digit jika itu user, tampilkan resi jika kurir
                'subtitle' => 'Input PIN/Resi: <span class="font-mono text-[#577A9F]">' . ($log->method == 'keypad' && strlen($log->input_value) == 4 ? $log->input_value : ($log->input_value ?? 'Sensor')) . '</span>',
                'actor' => $log->actor_type === 'courier'
                    ? 'Kurir'
                    : ($log->actor_type === 'user'
                        ? 'Pimilik'
                        : 'Tidak Dikenal'),
                'method' => 'Metode: ' . strtoupper($log->method)
            ],
            default => [
                'badge' => ['text' => 'SISTEM', 'class' => 'bg-gray-100 text-gray-600'],
                'title' => 'Aktivitas Sistem (' . $log->event . ')',
                'subtitle' => 'Detail: ' . ($log->input_value ?? '-'),
                'actor' => 'Sistem',
                'method' => 'Otomatis'
            ]
        };
    }
}
