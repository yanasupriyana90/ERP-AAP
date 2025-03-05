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
                'department_id' => fake()->numberBetween(1, 4),
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
                'department_id' => fake()->numberBetween(1, 4),
                'name' => 'Manager',
                'email' => 'manager@manager.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Manager',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => fake()->numberBetween(1, 4),
                'name' => 'Supervisor',
                'email' => 'supervisor@supervisor.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Supervisor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => fake()->numberBetween(1, 4),
                'name' => 'Staff',
                'email' => 'staff@staff.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Staff',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => fake()->numberBetween(1, 4),
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
