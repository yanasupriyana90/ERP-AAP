<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            [
                'name' => 'Superuser',
                'code' => 'SPR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Direktur',
                'code' => 'CEO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance',
                'code' => 'FNC',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Information Technology',
                'code' => 'MIS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operational',
                'code' => 'OPR',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
