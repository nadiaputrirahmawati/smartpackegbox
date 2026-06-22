<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MoneySlot;
use App\Models\Package;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Package::where('user_id', $request->user()->id);

        // Filter Search (Berdasarkan Nomor Resi)
        if ($request->filled('search')) {
            $query->where('tracking_number', 'like', '%' . $request->search . '%');
        }

        // Filter Payment Type (Prepaid / COD)
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter Package Status (Waiting / Arrived / dll)
        if ($request->filled('package_status')) {
            $query->where('package_status', $request->package_status);
        }

        // Jangan lupa paginate dan bawa query string agar pagination tidak rusak saat difilter
        $packages = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Users/Package/Index', [
            // Gunakan ->through() jika Anda memformat data sebelumnya, atau langsung kirim
            'packages' => $packages,
            'filters' => $request->only(['search', 'payment_type', 'package_status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data slot uang untuk ditampilkan di frontend
        $slots = MoneySlot::orderBy('slot_number', 'asc')->get();

        return Inertia::render('Users/Package/Create', [
            'slots' => $slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|unique:packages,tracking_number',
            'payment_type' => 'required|in:prepaid,cod',
            'amount' => 'required_if:payment_type,cod|numeric|min:0',
            'slot_id' => [
                'required_if:payment_type,cod',
                'nullable',
                Rule::exists('money_slots', 'id')->where(function ($query) {
                    // Pastikan slot yang dipilih statusnya 'empty'
                    $query->where('status', 'empty');
                }),
            ],
        ], [
            'tracking_number.unique' => 'Nomor resi ini sudah terdaftar di dalam sistem.',
            'slot_id.exists' => 'Slot uang yang dipilih tidak valid atau sudah terisi.',
            'amount.required_if' => 'Nominal uang wajib diisi untuk tipe pembayaran COD.',
            'slot_id.required_if' => 'Slot uang wajib dipilih untuk tipe pembayaran COD.'
        ]);

        // ====================================================================
        // 🔴 TAMBAHAN: VALIDASI DATA 4 DIGIT TERAKHIR RESI (ANTI BENTROK KEYPAD)
        // ====================================================================
        $newTrackingNumber = $validated['tracking_number'];
        $lastFourDigits = substr($newTrackingNumber, -4);

        // Cek apakah ada paket aktif (waiting/arrived) yang punya 4 digit ekor yang sama
        $isDuplicateLastFour = Package::whereRaw("RIGHT(tracking_number, 4) = ?", [$lastFourDigits])
            ->whereIn('package_status', ['waiting', 'arrived'])
            ->exists();

        if ($isDuplicateLastFour) {
            return back()->withErrors([
                'tracking_number' => "Gagal! 4 digit terakhir resi ini ({$lastFourDigits}) bentrok dengan paket aktif lain. Silakan gunakan nomor resi lain agar kurir tidak salah input di Keypad."
            ])->withInput(); // withInput agar form user tidak terhapus otomatis saat reload
        }
        // ====================================================================

        // Simpan paket
        $package = new Package();
        $package->user_id = $request->user()->id;
        $package->tracking_number = $validated['tracking_number'];
        $package->payment_type = $validated['payment_type'];

        if ($validated['payment_type'] === 'cod') {
            $package->amount = $validated['amount'];
            $package->slot_id = $validated['slot_id'];
            $package->payment_status = 'pending'; // COD belum dibayar

            // Update status slot menjadi terisi (filled)
            $slot = MoneySlot::find($validated['slot_id']);
            if ($slot) {
                $slot->status = 'filled';
                $slot->save();
            }
        } else {
            $package->amount = 0;
            $package->slot_id = null;
            $package->payment_status = 'deposited'; // Prepaid dianggap sudah lunas/tidak perlu bayar di tempat
        }

        $package->package_status = 'waiting';
        $package->save();

        return redirect()->route('user.packages.index')->with('success', 'Paket berhasil didaftarkan ke sistem.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data paket beserta relasi slot dan riwayat setoran (deposit_logs)
        $package = Package::with(['slot', 'depositLogs' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Urutkan riwayat dari yang terbaru
        }])->findOrFail($id);

        return Inertia::render('Users/Package/Show', [
            'package' => $package
        ]);
    }

    // ==============================================================
    // 1. MEMBUKA SLOT DAN MEMULAI SESI (CACHE + DB)
    // ==============================================================
    public function openSlot(string $id)
    {
        $package = Package::with('slot')->findOrFail($id);

        if ($package->payment_type !== 'cod' || !$package->slot_id) {
            return response()->json(['message' => 'Paket ini tidak memerlukan akses slot uang.'], 400);
        }

        // --- CUKUP UBAH WAKTU DI SINI ---
        $waktuMulai = 5; // Contoh: 2 menit
        // --------------------------------

        $expiresAt = Carbon::now()->addMinutes($waktuMulai);
        Cache::put("package_session_{$id}", $expiresAt, Carbon::now()->addMinutes($waktuMulai + 1));

        $payload = json_encode([
            'action' => 'insert_money_user',
            'package_id' => $package->id,
            'slot_number' => $package->slot->slot_number
        ]);

        try {
            $mqtt = new MqttService();
            $mqtt->publish('smartbox/rotary/command', $payload);

            return response()->json([
                'message' => 'Perintah berhasil dikirim ke perangkat IoT.',
                // KASIH TAHU FRONTEND BERAPA DETIK WAKTUNYA
                'remaining_seconds' => $waktuMulai * 60
            ]);
        } catch (\Exception $e) {
            Log::error('MQTT Publish Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal terhubung.'], 500);
        }
    }

    // ==============================================================
    // 2. VUE MENGECEK SISA WAKTU (PURE CACHE)
    // ==============================================================
    public function sessionStatus(string $id)
    {
        // Langsung tembak ke Redis (Sangat Cepat, tidak membebani Database)
        $remaining = Cache::get("package_session_{$id}");

        // Jika di Redis sudah hilang/dihapus, berarti sesi habis
        if ($remaining === null) {
            return response()->json(['remaining_seconds' => 0]);
        }

        // Hitung sisa waktu aktual
        $expiresAt = Carbon::parse($remaining);
        $diff = Carbon::now()->diffInSeconds($expiresAt, false); // false = izinkan nilai minus

        return response()->json([
            'remaining_seconds' => $diff > 0 ? $diff : 0
        ]);
    }

    // ==============================================================
    // 3. VUE MEMPERPANJANG SESI (CACHE + DB)
    // ==============================================================
    public function extendSession(string $id)
    {
        $package = Package::findOrFail($id);

        // --- CUKUP UBAH WAKTU DI SINI ---
        $waktuTunggu = 2; // Contoh: Perpanjang 5 menit
        // --------------------------------

        $newExpiresAt = Carbon::now()->addMinutes($waktuTunggu);
        Cache::put("package_session_{$id}", $newExpiresAt, Carbon::now()->addMinutes($waktuTunggu + 1));

        return response()->json([
            'message' => 'Sesi diperpanjang.',
            // KASIH TAHU FRONTEND BERAPA DETIK WAKTUNYA
            'remaining_seconds' => $waktuTunggu * 60
        ]);
    }

    // ==============================================================
    // 4. MENGAKHIRI SESI & FORCE CLOSE ESP32
    // ==============================================================
    public function terminateSession(string $id)
    {
        $package = Package::findOrFail($id);

        // A. HAPUS CACHE (Ini paling krusial agar API sessionStatus langsung jawab 0)
        Cache::forget("package_session_{$id}");

        // B. Kosongkan DB
        // $package->update(['session_expires_at' => null]);

        // C. KIRIM PERINTAH FORCE CLOSE KE ESP32
        $payload = json_encode([
            'action' => 'force_close',
            'package_id' => $package->id
        ]);

        try {
            $mqtt = new MqttService();
            $mqtt->publish('smartbox/rotary/command', $payload);
        } catch (\Exception $e) {
            // Kita catat errornya, tapi tidak usah melempar status 500 ke Vue
            // karena dari sisi web, sesinya sudah berhasil ditutup.
            Log::error('MQTT Force Close Error: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Sesi diakhiri, ESP32 dikunci.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = Package::findOrFail($id);
        $slots = MoneySlot::orderBy('slot_number', 'asc')->get();

        return Inertia::render('Users/Package/Edit', [
            'package' => $package,
            'slots' => $slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'tracking_number' => 'required|string|unique:packages,tracking_number,' . $package->id,
            'payment_type' => 'required|in:prepaid,cod',
            'amount' => 'required_if:payment_type,cod|numeric|min:0',
            'slot_id' => [
                'required_if:payment_type,cod',
                'nullable',
                Rule::exists('money_slots', 'id')->where(function ($query) use ($package) {
                    // Slot valid jika statusnya 'empty' ATAU itu adalah slot milik paket ini sendiri
                    $query->where('status', 'empty')
                        ->orWhere('id', $package->slot_id);
                }),
            ],
        ], [
            'slot_id.exists' => 'Slot uang yang dipilih tidak valid atau sudah digunakan paket lain.',
            'amount.required_if' => 'Nominal uang wajib diisi untuk COD.',
            'slot_id.required_if' => 'Slot uang wajib dipilih untuk COD.'
        ]);

        // ATURAN 1: Dari COD mau pindah ke Prepaid
        if ($package->payment_type === 'cod' && $validated['payment_type'] === 'prepaid') {
            if ($package->payment_status !== 'pending') {
                return back()->with('error', 'Tidak dapat mengubah ke Prepaid. Pembayaran COD sudah diproses.');
            }

            // Kosongkan slot lama
            if ($package->slot_id) {
                $oldSlot = MoneySlot::find($package->slot_id);
                if ($oldSlot) {
                    $oldSlot->status = 'empty';
                    $oldSlot->save();
                }
            }

            $package->amount = 0;
            $package->slot_id = null;
            $package->payment_status = 'deposited';
        }

        // ATURAN 2: Dari Prepaid ke COD ATAU COD ganti slot
        if ($validated['payment_type'] === 'cod') {
            // Jika slot berubah
            if ($package->slot_id !== $validated['slot_id']) {
                // Kosongkan slot lama (jika ada)
                if ($package->slot_id) {
                    $oldSlot = MoneySlot::find($package->slot_id);
                    if ($oldSlot) {
                        $oldSlot->status = 'empty';
                        $oldSlot->save();
                    }
                }
                // Isi slot baru
                $newSlot = MoneySlot::find($validated['slot_id']);
                if ($newSlot) {
                    $newSlot->status = 'filled';
                    $newSlot->save();
                }
            }

            $package->amount = $validated['amount'];
            $package->slot_id = $validated['slot_id'];

            // Jika sebelumnya prepaid, kembalikan statusnya ke pending
            if ($package->payment_type === 'prepaid') {
                $package->payment_status = 'pending';
            }
        }

        $package->tracking_number = $validated['tracking_number'];
        $package->payment_type = $validated['payment_type'];
        $package->save();

        return redirect()->route('user.packages.index')->with('success', 'Data paket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::findOrFail($id);
        if ($package->payment_status === 'deposited' && $package->package_status === 'waiting') {
            return back()->with('error', 'Tidak dapat menghapus paket. Paket sudah dibayar.');
        }
        $package->delete();
    }

    public function showReceiptDetail($id)
    {
        // 1. Ambil data paket (Sesuaikan dengan relasi dan struktur tabel Anda)
        $package = Package::findOrFail($id);

        // 2. Ambil log aktivitas penerimaan (contoh: action_type 'scan_barcode' atau 'package_inserted')
        // yang memiliki image_path
        $receiptLog = ActivityLog::where('package_id', $id)
            ->whereNotNull('image_path')
            ->latest()
            ->first();

        // 3. Format data waktu
        $arrivalTime = $receiptLog ? Carbon::parse($receiptLog->created_at) : Carbon::parse($package->created_at);

        // 4. Kirim data ke Vue
        return Inertia::render('Users/Package/ReceiptDetail', [
            'packageInfo' => [
                'user_id' => $package->user_id,
                'package_id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'transaction_id' => str_pad($package->id, 5, '0', STR_PAD_LEFT) . '-' . $arrivalTime->format('Y'),
                'status' => 'received',
                'arrival_time_formatted' => $arrivalTime->translatedFormat('d M Y, H:i') . ' WIB',
            ],
            'receiptImage' => [
                // Jika tidak ada gambar, gunakan placeholder
                'url' => $receiptLog && $receiptLog->image_path ? asset('storage/' . $receiptLog->image_path) : '/placeholder-box.jpg',
                'camera_name' => 'CAM-01 INSIDE-BOX',
                'timestamp_overlay' => $arrivalTime->translatedFormat('d M Y | H:i:s'),
                'quality' => '4K UHD'
            ]
        ]);
    }

    public function confirmMoneyDeposit(Request $request, Package $package)
    {
        $request->validate([
            'is_valid' => 'required|boolean',
            'nominal' => 'required_if:is_valid,true|numeric',
            'color_signature' => 'nullable|string'
        ]);

        $mqtt = new \App\Services\MqttService();

        // JIKA PENGGUNA MENEKAN TOMBOL "BATAL / SALAH"
        if (!$request->is_valid) {
            // Perintahkan ESP32 untuk lanjut scan
            $mqtt->publish('smartbox/rotary/command', json_encode([
                'action' => 'cancel_money'
            ]));
            return response()->json(['message' => 'Dibatalkan.']);
        }

        // JIKA PENGGUNA MENEKAN TOMBOL "BENAR"
        $nominal = $request->nominal;

        DB::transaction(function () use ($package, $nominal, $request) {
            // 1. Simpan Uang
            $deposit = \App\Models\DepositLog::create([
                'package_id' => $package->id,
                'nominal' => $nominal,
                'color_signature' => $request->color_signature ?? 'Unknown'
            ]);

            $package->total_received += $nominal;

            // 2. Cek Lunas
            if ($package->total_received >= $package->amount) {
                $package->payment_status = 'deposited';
            }
            $package->save();

            // Hitung kelebihan jika ada
            if ($package->payment_status === 'deposited' && $package->total_received > $package->amount) {
                $package->overpayment = $package->total_received - $package->amount;
            } else {
                $package->overpayment = 0;
            }

            // 3. Broadcast Event Lunas (Biar animasi & suara koin di Vue jalan)
            $event = new \App\Events\MoneyDeposited($package, $deposit);
            broadcast($event);
        });

        // 4. Perintahkan ESP32 melanjutkan
        if ($package->payment_status === 'deposited') {
            \Illuminate\Support\Facades\Cache::forget("package_session_{$package->id}");
            $mqtt->publish('smartbox/rotary/command', json_encode([
                'action' => 'force_close',
                'package_id' => $package->id
            ]));
        } else {
            // Belum lunas, suruh ESP32 buka kunci sensornya lagi
            $mqtt->publish('smartbox/rotary/command', json_encode([
                'action' => 'confirm_money'
            ]));
        }

        return response()->json(['message' => 'Berhasil dikonfirmasi!']);
    }
}
