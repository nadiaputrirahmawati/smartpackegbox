<?php 
return [
    'host' => env('MQTT_HOST', 'test.mosquitto.org'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', null),
    'password' => env('MQTT_PASSWORD', null),
    'client_id' => env('MQTT_CLIENT_ID', 'laravel_gateway')
];

?>