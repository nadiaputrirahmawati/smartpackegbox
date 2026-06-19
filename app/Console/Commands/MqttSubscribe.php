<?php

namespace App\Console\Commands;

use App\Events\MoneyDeposited;
use Illuminate\Console\Command;
use App\Models\Package;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\MoneySlot;
use App\Services\MqttService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt-subscribe';
    protected $description = 'Listen to SmartBox IoT Sensors (Box & Rotary)';

    // Variabel pembantu untuk melacak status pengambilan uang COD di Laravel
    protected $activeCodTriggers = [];

    public function handle()
    {
        // 🛡️ OPTIMASI 1: Matikan Query Log agar RAM tidak bocor saat script berjalan berhari-hari
        DB::connection()->disableQueryLog();

        $this->info('🚀 Memulai koneksi ke Broker MQTT (Optimized Edition)...');
        $mqtt = new MqttService();

        // ==============================================================
        // SUBSCRIBER 1: BOX REQUEST (OPTIMIZED FOR PIN USER)
        // ==============================================================
        $mqtt->subscribe('smartbox/box/request', function ($topic, $message) use ($mqtt) {
            try {
                $this->pingDatabase();

                $data = json_decode($message, true);
                if (!$data || !isset($data['input_key'])) return;

                $input = trim($data['input_key']);
                $type = $data['type']; // 'scan' atau 'keypad'
                $inputLength = strlen($input);

                // 1. Buat Log Awal (Status Default: failed)
                $log = ActivityLog::create([
                    'input_value' => $input,
                    'method'      => ($type == 'scan') ? ActivityLog::METHOD_SCAN : ActivityLog::METHOD_KEYPAD,
                    'event'       => ActivityLog::EVENT_ACCESS_DENIED,
                    'status'      => 'failed',
                ]);

                // Trigger Kamera
                $mqtt->publish('smartbox/camera/trigger', json_encode([
                    'log_id' => $log->id,
                    'input_value' => $input,
                ]));

                // ==============================================================
                // RUTE KHUSUS 1: JIKA INPUT ADALAH KEYPAD DAN PANJANGNYA >= 5 DIGIT (PASTI PIN USER)
                // ==============================================================
                if ($type == 'keypad' && $inputLength >= 5) {
                    $user = User::where('pin', $input)->first();
                    
                    if ($user) {
                        $mqtt->publish('smartbox/box/command', $user->letter_box . "_USER");
                        $mqtt->publish('smartbox/lcd/update', 'PIN Benar, Terbuka');

                        $log->update([
                            'user_id'    => $user->id,
                            'status'     => 'success',
                            'actor_type' => ActivityLog::ACTOR_USER,
                            'event'      => ActivityLog::EVENT_DOOR_OPENED 
                        ]);
                    } else {
                        $mqtt->publish('smartbox/lcd/update', 'PIN Salah / Gagal');
                        $mqtt->publish('smartbox/box/command', 'STANDBY'); 
                    }
                    return; 
                }

                // ==============================================================
                // RUTE KHUSUS 2: CEK SEBAGAI PAKET (SCAN RESI ATAU KEYPAD KODE 4 DIGIT)
                // ==============================================================
                $paket = null;
                if ($type == 'scan') {
                    $paket = Package::with(['user', 'slot'])->where('tracking_number', $input)->first();
                } elseif ($type == 'keypad' && $inputLength == 4) {
                    $paket = Package::with(['user', 'slot'])->whereRaw("RIGHT(tracking_number, 4) = ?", [$input])->first();
                }

                if ($paket) {
                    if ($paket->package_status === 'arrived') {
                        $mqtt->publish('smartbox/lcd/update', 'Paket Selesai');
                        $mqtt->publish('smartbox/box/command', 'STANDBY');
                        return;
                    }

                    if ($paket->user && $paket->package_status === 'waiting') {
                        // --- LOGIKA PREPAID vs COD ---
                        if ($paket->payment_type === 'prepaid') {
                            $mqtt->publish('smartbox/lcd/update', 'Masukan paket ke slot ' . $paket->user->letter_box);
                        } elseif ($paket->payment_type === 'cod') {
                            if ($paket->payment_status !== 'deposited') {
                                $mqtt->publish('smartbox/lcd/update', 'Uang Belum Tersedia Hubungi Pemilik');
                                $mqtt->publish('smartbox/box/command', 'STANDBY');

                                $log->update([
                                    'package_id' => $paket->id,
                                    'status'     => 'failed',
                                    'event'      => 'payment_not_ready',
                                ]);
                                return;
                            }

                            // Alur Baru: Beritahu kurir untuk masukkan paket terlebih dahulu
                            $mqtt->publish('smartbox/lcd/update', 'Masukan paket ke slot ' . $paket->user->letter_box);
                        }

                        // --- BUKA PINTU PAKET NODE A ---
                        $mqtt->publish('smartbox/box/command', json_encode([
                            'tracking_number' => $paket->tracking_number,
                            'action' => $paket->user->letter_box . "_KURIR"
                        ]));

                        $log->update([
                            'package_id' => $paket->id,
                            'status'     => 'success',
                            'actor_type' => 'courier', 
                            'event'      => 'door_opened' 
                        ]);
                        return;
                    }
                } 
                
                $mqtt->publish('smartbox/lcd/update', 'Data Tidak Valid');
                $mqtt->publish('smartbox/box/command', 'STANDBY');
                
            } catch (\Exception $e) {
                Log::error("MQTT Error: " . $e->getMessage());
            }
        });

        // ==============================================================
        // SUBSCRIBER 2: DOOR STATUS
        // ==============================================================
        $mqtt->subscribe('smartbox/box/door/status', function ($topic, $message) {
            try {
                $this->pingDatabase();

                $data = json_decode($message, true);
                if (!isset($data['box'], $data['door'], $data['status'])) return;

                // Pastikan ini adalah pintu belakang/user
                if ($data['door'] === 'rear') {
                    $boxLetter = $data['box'];
                    $status = $data['status'];

                    // Cari user pemilik Box berdasarkan field letter_box (Berisi 'A' atau 'B')
                    $user = \App\Models\User::where('letter_box', $boxLetter)->first();

                    if ($user) {
                        
                        // KONDISI 1: Jika ini merupakan sinyal Kelalaian Pintu Terbuka Lama
                        if ($status === 'timeout_open') {
                            $this->warn("⚠️ Peringatan! Pintu Box {$boxLetter} milik User ID: {$user->id} terbuka terlalu lama.");
                            
                            // Panggil NotificationService langsung secara instan menuju HP/Browser user
                            $notificationService = new \App\Services\NotificationService();
                            $notificationService->sendDoorAlert($user->id, $boxLetter);
                        } 
                        // KONDISI 2: Jika ini kondisi buka/tutup normal, teruskan broadcast websockets Realtime Vue Anda
                        else {
                            broadcast(new \App\Events\RearDoorStatusUpdated($user->id, $status));
                            $this->info("Status pintu belakang {$boxLetter} menjadi: {$status}");
                        }

                    }
                }
            } catch (\Exception $e) {
                Log::error("MQTT Box Status Error: " . $e->getMessage());
            }
        });

        // =========================================================
        // SUBSCRIBER 3: VERIFY PAKET (LOADCELL DETEKSI BARANG MASUK)
        // =========================================================
        $mqtt->subscribe('smartbox/box/verify', function ($topic, $message) use ($mqtt) {
            try {
                $this->pingDatabase();

                $data = json_decode($message, true);
                if (!isset($data['tracking_number'], $data['box'])) return;

                $trackingNumber = $data['tracking_number'];
                $box = $data['box'];

                $packageId = null;
                $targetUserId = null;
                $userName = null;
                DB::transaction(function () use ($trackingNumber, $box, $mqtt, &$packageId, &$targetUserId, &$userName) {
                    $package = Package::where('tracking_number', $trackingNumber)->first();

                    if ($package) {
                        $packageId = $package->id;
                        $targetUserId = $package->user_id;
                        $userName = $package->user->name ?? 'Pengguna';

                        $package->update([
                            'package_status' => 'arrived',
                            'arrived_at'     => now()
                        ]);

                        $log = ActivityLog::create([
                            'package_id'  => $package->id,
                            'user_id'     => $package->user_id,
                            'input_value' => $trackingNumber,
                            'method'      => ActivityLog::METHOD_SCAN, 
                            'event'       => ActivityLog::EVENT_PACKAGE_ARRIVED,
                            'status'      => 'success',
                            'actor_type'  => ActivityLog::ACTOR_COURIER,
                        ]);

                        // 🔥 JIKA COD: Di sinilah pemicu perputaran Rotary Box setelah barang masuk boks
                        if ($package->payment_type === 'cod') {
                            if ($package->slot) {
                                $slotNum = $package->slot->slot_number;
                                
                                // 1. Kirim perintah ke Node B untuk arahkan slot uang kurir
                                $mqtt->publish('smartbox/rotary/command', json_encode([
                                    'action' => 'open_slot_courier',
                                    'package_id' => $package->id,
                                    'slot_number' => $slotNum
                                ]));
                                
                                Log::info("🔓 Paket Masuk Box Bawah! Perintah putar kotak uang dikirim ke Node B Slot {$slotNum}.");

                                // 2. Daftarkan ke sistem pengingat (Spam LCD) agar teks "Ambil Uang" menetap selama 30 detik
                                $this->activeCodTriggers[$package->id] = [
                                    'start_time' => time(),
                                    'last_push' => 0
                                ];

                                MoneySlot::where('id', $package->slot->id)->update(['status' => 'empty']);
                            }
                        }

                        $mqtt->publish('smartbox/camera/trigger', json_encode([
                            'log_id' => $log->id,
                            'input_value' => $trackingNumber,
                        ]));

                        // Tampilan awal saat loadcell sukses mendeteksi barang
                        if ($package->payment_type === 'cod') {
                            $mqtt->publish('smartbox/lcd/update', 'Ambil Uang COD Diatas!');
                        } else {
                            $mqtt->publish('smartbox/lcd/update', 'Paket Diterima, Terima Kasih!');
                        }
                        
                        Log::info("📦 Paket $trackingNumber terverifikasi sensor LOADCELL di Kotak $box");
                    }
                });

                if ($packageId && $targetUserId) {
                    $notificationService = new \App\Services\NotificationService();
                    $notificationService->sendNotificationAsync($packageId, $targetUserId, $userName);
                    
                    Log::info("🚀 Pusher Beams triggered instan untuk User ID: {$targetUserId} pada Paket ID: {$packageId}");
                }
            } catch (\Exception $e) {
                Log::error("Gagal Verifikasi LOADCELL: " . $e->getMessage());
            }
        });

        // ==============================================================
        // SUBSCRIBER 4: ROTARY SENSOR
        // ==============================================================
        $mqtt->subscribe('smartbox/rotary/sensor', function ($topic, $message) {
            try {
                $this->pingDatabase();

                $data = json_decode($message, true);
                $this->info("Rotary data terima: " . json_encode($data));
                if (!$data || !isset($data['action'])) return;

                $packageId = $data['package_id'];
                $package = Package::find($packageId);
                if (!$package) return;

                if ($data['action'] === 'money_pending_confirmation') {
                    $sessionExpiry = \Illuminate\Support\Facades\Cache::get("package_session_{$package->id}");
                    if (!$sessionExpiry || \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($sessionExpiry))) {
                        $this->error("Uang ditolak! Sesi sudah habis.");
                        return;
                    }

                    $nominal = $data['nominal'];
                    $colorSignature = $data['color_signature'] ?? 'Unknown';

                    $event = new \App\Events\MoneyPendingConfirmation($package, $nominal, $colorSignature);
                    broadcast($event);
                    
                    $this->info("Menunggu konfirmasi web untuk Rp {$nominal}");
                }
                else if ($data['action'] === 'unrecognized_money') {
                    $event = new \App\Events\MoneyUnrecognized($package);
                    broadcast($event);
                    $this->error("Peringatan: Uang gagal dikenali di Rotary Box.");
                }
                else if ($data['action'] === 'position_reached') {
                    $this->info("Rotary siap di Slot {$data['slot_number']} untuk paket ID: {$package->id}");
                }

            } catch (\Exception $e) {
                Log::error("MQTT Rotary Sensor Error: " . $e->getMessage());
            }
        });

        // ==============================================================
        // LOOP DAEMON (DENGAN MANAJEMEN TIMER PENGINGAT LCD LIVE)
        // ==============================================================
        $this->info('📡 Menunggu pesan dari broker...');

        while (true) {
            try {
                $mqtt->loop();
                
                // 🔥 LOGIKA PROFESIONAL: Pengingat (Spam) LCD untuk Kurir COD
                // Kode ini berjalan setiap loop untuk memantau apakah ada kurir yang sedang mengambil uang
                foreach ($this->activeCodTriggers as $pkgId => $timerData) {
                    $currentTime = time();
                    
                    // Jika sudah berjalan lebih dari 30 detik, hapus dari pengingat laravel
                    if ($currentTime - $timerData['start_time'] >= 30) {
                        unset($this->activeCodTriggers[$pkgId]);
                        $mqtt->publish('smartbox/lcd/update', 'Transaksi Selesai, Terima Kasih!');
                        continue;
                    }

                    // Spam pesan ke LCD setiap 4 detik sekali agar menabrak fungsi reset lokal di ESP Node A
                    if ($currentTime - $timerData['last_push'] >= 4) {
                        $mqtt->publish('smartbox/lcd/update', 'Ambil Uang COD Diatas!');
                        $this->activeCodTriggers[$pkgId]['last_push'] = $currentTime;
                    }
                }

                // 🛡️ OPTIMASI 3: Penahan CPU usage agar tidak menembus 100%.
                usleep(50000); 

            } catch (\Exception $e) {
                $this->error("⚠️ MQTT Loop Error: " . $e->getMessage());
                sleep(2); 
            }
        }
    }

    private function pingDatabase()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->warn("🔄 Koneksi Database Terputus. Melakukan Reconnect...");
            DB::purge('mysql'); 
            DB::reconnect('mysql');
            DB::connection()->disableQueryLog(); 
        }
    }
}