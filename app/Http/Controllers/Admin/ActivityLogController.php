<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
   public function index(Request $request)
    {
        $query = ActivityLog::with(['package', 'user'])->latest();

        // 1. Filter Pencarian (Berdasarkan Resi atau Input Value)
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('input_value', 'like', '%' . $request->search . '%')
                  ->orWhereHas('package', function($sub) use ($request) {
                      $sub->where('tracking_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // 2. Filter Tanggal
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(10)->withQueryString()->through(function ($log) {
            $isFailed = $log->status === 'failed' || $log->event === 'access_denied';
            
            $actorName = match ($log->actor_type) {
                'user' => $log->user ? $log->user->name : 'Pemilik',
                'courier' => 'Kurir Ekspedisi',
                'system' => 'Sistem IoT',
                default => 'Tidak Dikenal'
            };

            $title = match ($log->event) {
                'door_opened' => 'Pintu Dibuka',
                'package_arrived' => 'Paket Diterima',
                'door_closed' => 'Pintu Ditutup',
                default => 'Aktivitas Sistem'
            };

            if ($isFailed) $title = 'Akses Ditolak';

            return [
                'id' => $log->id,
                'date' => $log->created_at->format('d M Y'),
                'time' => $log->created_at->format('H:i:s'),
                'title' => $title,
                'actor' => $actorName,
                'input_data' => $log->package ? $log->package->tracking_number : ($log->input_value ?? 'Tanpa Data'),
                'method' => $log->method ?? 'otomatis',
                'status' => $isFailed ? 'Gagal' : 'Sukses',
                'image_url' => $log->image_path ? Storage::url($log->image_path) : null,
            ];
        });

        $stats = [
            'total_packages' => Package::count(),
        ];

        return Inertia::render('Admin/ActivityLog', [
            'logs' => $logs,
            'stats' => $stats,
            // Kirim filter date kembali ke Vue agar state-nya terjaga
            'filters' => $request->only(['search', 'date']) 
        ]);
    }

public function exportPdf(Request $request)
    {
        $query = ActivityLog::with(['package', 'user'])->latest();

        // 1. Filter Pencarian (Tetap dibawa jika sedang mencari sesuatu)
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('input_value', 'like', '%' . $request->search . '%')
                  ->orWhereHas('package', function($sub) use ($request) {
                      $sub->where('tracking_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // 2. FILTER TANGGAL KHUSUS EXPORT
        if ($request->export_type === 'range' && $request->start_date && $request->end_date) {
            // Rentang Tanggal (Mulai dari jam 00:00:00 sampai 23:59:59)
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
            $fileName = 'Log_Aktivitas_' . $request->start_date . '_sd_' . $request->end_date . '.pdf';
        } 
        elseif ($request->export_type === 'single' && $request->date) {
            // Satu Hari Saja
            $query->whereDate('created_at', $request->date);
            $fileName = 'Log_Aktivitas_' . $request->date . '.pdf';
        } 
        else {
            // Semua Waktu
            $fileName = 'Log_Aktivitas_Semua_Waktu.pdf';
        }

        $logs = $query->get();

        // Mapping Data untuk PDF
        $formattedLogs = $logs->map(function ($log) {
            $isFailed = $log->status === 'failed' || $log->event === 'access_denied';
            
            $actorName = match ($log->actor_type) {
                'user' => $log->user ? $log->user->name : 'Pemilik',
                'courier' => 'Kurir Ekspedisi',
                'system' => 'Sistem IoT',
                default => 'Tidak Dikenal'
            };

            $title = match ($log->event) {
                'door_opened' => 'Pintu Dibuka',
                'package_arrived' => 'Paket Diterima',
                'door_closed' => 'Pintu Ditutup',
                default => 'Aktivitas Sistem'
            };

            if ($isFailed) $title = 'Akses Ditolak';

            return (object) [
                'tanggal' => $log->created_at->format('d M Y H:i:s'),
                'aktivitas' => $title,
                'aktor' => $actorName,
                'data_input' => $log->package ? $log->package->tracking_number : ($log->input_value ?? 'Tanpa Data'),
                'metode' => ucfirst($log->method ?? 'otomatis'),
                'status' => $isFailed ? 'Gagal' : 'Sukses',
            ];
        });

        $pdf = Pdf::loadView('pdf.activity_logs', ['logs' => $formattedLogs]);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download($fileName);
    }
}
