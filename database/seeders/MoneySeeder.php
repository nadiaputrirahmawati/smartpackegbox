<?php

namespace Database\Seeders;

use App\Models\MoneySlot;
use Illuminate\Database\Seeder;

class MoneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MoneySlot::insert([
            [
                'slot_number' => 1,
                'status' => 'empty'
            ],
            [
                'slot_number' => 2,
                'status' => 'empty'
            ],
            [
                'slot_number' => 3,
                'status' => 'empty'
            ],
            [
                'slot_number' => 4,
                'status' => 'empty'
            ]
        ]);
    }
}
