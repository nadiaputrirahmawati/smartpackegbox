<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneySlot extends Model
{
    protected $table = 'money_slots';

    protected $fillable = [
        'slot_number',
        'status',
    ];
}
