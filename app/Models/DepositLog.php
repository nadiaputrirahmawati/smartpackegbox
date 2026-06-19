<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositLog extends Model
{
    protected $fillable = [
        'package_id',
        'nominal',
        'color_signature',
    ];

    /**
     * Relasi ke paket. Satu log setoran milik satu paket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
