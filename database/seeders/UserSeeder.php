<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'department_id' => null,
                'name' => 'Superuser',
                'email' => 'superuser@superuser.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Superuser',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 2,
                'name' => 'Direktur',
                'email' => 'direktur@direktur.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Direktur',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'name' => 'Manager Finance',
                'email' => 'mgr_fin@mgr.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Manager',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'name' => 'Supervisor Finance',
                'email' => 'spv_fin@spv.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Supervisor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'name' => 'Staff Finance',
                'email' => 'staff_fin@staff.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Staff',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 4,
                'name' => 'Manager Operation',
                'email' => 'mgr_opr@mgr.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Manager',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 4,
                'name' => 'Supervisor Operation',
                'email' => 'spv_opr@spv.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Supervisor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 4,
                'name' => 'Staff Operation',
                'email' => 'staff_opr@staff.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Staff',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
