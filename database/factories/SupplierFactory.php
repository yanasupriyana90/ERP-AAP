<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = date('y'); // Tahun sekarang, misal: 25 untuk 2025
        $month = str_pad(random_int(1, 12), 2, '0', STR_PAD_LEFT); // Bulan (01-12)
        $day = str_pad(random_int(1, 31), 2, '0', STR_PAD_LEFT); // Tanggal (01-31)
        $sequence = str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT); // Nomor urut 5 digit

        return [
            'code' => "SUP-{$year}{$month}{$day}{$sequence}",
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'contact_person' => $this->faker->name,
            'user_id' => 1,
        ];
    }
}
