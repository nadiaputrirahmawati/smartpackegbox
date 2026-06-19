<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected $client;
    protected $settings; // Simpan settings

    public function __construct()
    {
        $server   = config('mqtt.host');
        $port     = config('mqtt.port');
        $clientId = config('mqtt.client_id') . '-backend-' . now()->timestamp;

        $this->settings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setUseTls(false);

        $this->client = new MqttClient($server, $port, $clientId);
        $this->connectWithRetry();
    }

    public function connectWithRetry()
    {
        while (true) {
            try {
                $this->client->connect($this->settings, true);
                Log::info("✅ Terhubung ke MQTT Broker.");
                break; // Keluar dari loop jika berhasil
            } catch (\Exception $e) {
                Log::warning("⚠️ Gagal konek MQTT: " . $e->getMessage() . " | Retrying in 5s...");
                sleep(5); // Jeda 5 detik sebelum mencoba lagi (Cegah CPU Spike)
            }
        }
    }

    public function publish($topic, $message)
    {
        try {
            $this->client->publish($topic, $message, 0);
        } catch (\Exception $e) {
            Log::error("❌ Gagal Publish MQTT: " . $e->getMessage());
        }
    }

    public function subscribe($topic, callable $callback)
    {
        $this->client->subscribe($topic, $callback, 0);
    }

    public function loop($duration = 1)
    {
        try {
            $this->client->loop(true, false, $duration);
        } catch (\Exception $e) {
            Log::error("🔌 Koneksi MQTT Terputus: " . $e->getMessage() . " | Mencoba Reconnect...");
            
            // Putuskan koneksi yang menggantung (abaikan error jika sudah putus)
            try { $this->client->disconnect(); } catch (\Exception $ex) {}
            
            // Lakukan reconnect ulang menggunakan settings
            $this->connectWithRetry();
        }
    }

    public function disconnect()
    {
        $this->client->disconnect();
    }
}