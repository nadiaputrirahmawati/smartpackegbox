<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'user_id',
        'method',
        'event',
        'input_value',
        'status',
        'actor_type',
        'image_path'
    ];

    // Gunakan ini di MqttSubscribe atau Controller agar tidak ada salah ketik
    const METHOD_SCAN = 'scan';
    const METHOD_KEYPAD = 'keypad';
    const METHOD_WEB = 'web';
    const METHOD_MAGNET = 'magnet_sensor';

    const EVENT_DOOR_OPENED = 'door_opened';
    const EVENT_DOOR_CLOSED = 'door_closed';
    const EVENT_PACKAGE_ARRIVED = 'package_arrived';
    const EVENT_ACCESS_DENIED = 'access_denied';

    const ACTOR_USER = 'user';
    const ACTOR_COURIER = 'courier';
    const ACTOR_SYSTEM = 'system';

    // Helper untuk memudahkan pengecekan
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
