<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'user_id',
        'tracking_number',
        'payment_type',
        'amount',
        'payment_status',
        'package_status',
        'slot_id',
        'arrived_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function slot()
    {
        return $this->belongsTo(MoneySlot::class);
    }

    public function depositLogs()
    {
        return $this->hasMany(DepositLog::class);
    }
}
