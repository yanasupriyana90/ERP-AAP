<?php

namespace Database\Factories;

use App\Models\BudgetDepartment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BudgetDepartment>
 */
class BudgetDepartmentFactory extends Factory
{
    protected $model = BudgetDepartment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $amount = $this->faker->numberBetween(10000000, 50000000); // Anggaran total
        // $usedAmount = $this->faker->numberBetween(0, $amount); // Anggaran yang sudah terpakai
        // $remainingAmount = $amount - $usedAmount; // Sisa anggaran

        $amount = $this->faker->randomElement([10000000, 20000000, 30000000, 40000000, 50000000]); // Anggaran total
        $usedAmount = 0; // Anggaran yang sudah terpakai
        $remainingAmount = $amount - $usedAmount; // Sisa anggaran

        return [
            'user_id' => 2,
            'department_id' => $this->faker->randomElement([3, 4]),
            'code' => 'BD-' . strtoupper(Str::random(5)),
            'name' => $this->faker->company() . ' Budget',
            'amount' => $amount,
            'used_amount' => $usedAmount,
            'remaining_amount' => $remainingAmount,
            'valid_from' => now()->startOfMonth(),
            'valid_to' => now()->endOfMonth(),
            'status' => 0,
            // 'status' => $this->faker->randomElement([0, 1]), // 0 = aktif, 1 = nonaktif
        ];
    }

}
