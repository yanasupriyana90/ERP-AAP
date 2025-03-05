<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert([
            ['code' => 'KG', 'name' => 'Kilogram', 'user_id' => 1],
            ['code' => 'L', 'name' => 'Liter', 'user_id' => 1],
            ['code' => 'M', 'name' => 'Meter', 'user_id' => 1],
            ['code' => 'UNIT', 'name' => 'Unit', 'user_id' => 1],
            ['code' => 'PCS', 'name' => 'Pieces', 'user_id' => 1],
            ['code' => 'SET', 'name' => 'Set', 'user_id' => 1],
        ]);
    }
}
