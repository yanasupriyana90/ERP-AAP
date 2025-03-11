<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 10);
        $unitPrice = $this->faker->numberBetween(50000, 500000);
        $totalPrice = $quantity * $unitPrice;

        return [
            'po_id' => PurchaseOrder::inRandomOrder()->first()->id ?? PurchaseOrder::factory(),
            'item_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'quantity' => $quantity,
            'unit_id' => Unit::inRandomOrder()->first()->id ?? Unit::factory(),
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ];
    }
}
