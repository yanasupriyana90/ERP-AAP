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
                'name' => 'Board Of Directors',
                'code' => 'BOD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operation',
                'code' => 'OPR',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
