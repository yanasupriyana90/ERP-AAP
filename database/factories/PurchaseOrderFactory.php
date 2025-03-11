<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Department;
use App\Models\BudgetDepartment;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
            'budget_department_id' => BudgetDepartment::inRandomOrder()->first()->id ?? BudgetDepartment::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()->id ?? Supplier::factory(),
            'po_number' => 'PO-' . strtoupper(Str::random(6)),
            'po_date' => $this->faker->date(),
            'total_amount' => 0, // Akan dihitung ulang setelah items ditambahkan
            'status' => 0, // 0 = Pending, 1 = Approved, 2 = Rejected
            // 'status' => $this->faker->randomElement([0, 1, 2]), // 0 = Pending, 1 = Approved, 2 = Rejected
            'notes' => $this->faker->sentence(),
        ];
    }
}
