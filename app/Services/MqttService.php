<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\DataTransferException;

class MqttService
{
    protected $client;

    public function __construct()
    {
        $server   = config('mqtt.host');
        $port     = config('mqtt.port');
        $clientId = config('mqtt.client_id');

        $settings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setUseTls(false);

        $this->client = new MqttClient($server, $port, $clientId);
        $this->client->connect($settings, true);
    }

    public function publish($topic, $message)
    {
        $this->client->publish($topic, $message, 0);
    }

    public function subscribe($topic, callable $callback)
    {
        $this->client->subscribe($topic, $callback, 0);
    }

    // public function loop($duration = 5)
    // {
    //     $this->client->loop(true, true, $duration);
    // }

     public function loop($duration = 1)
    {
        try {
            $this->client->loop(true, false, $duration);
        } catch (DataTransferException $e) {
            // reconnect otomatis jika socket tertutup
            $this->disconnect();
            $this->client->connect();
        }
    }

    public function disconnect()
    {
        $this->client->disconnect();
    }
}
