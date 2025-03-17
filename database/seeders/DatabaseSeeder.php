<?php

namespace Database\Seeders;

use App\Models\BudgetDepartment;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            UnitSeeder::class,
        ]);

        // BudgetDepartment::factory()->count(10)->create();

        // // Generate 10 Purchase Orders
        // PurchaseOrder::factory(10)->create()->each(function ($po) {
        //     // Generate 3-5 Items per Purchase Order
        //     $items = PurchaseOrderItem::factory(rand(3, 5))->create(['po_id' => $po->id]);

        //     // Hitung total amount dari semua items
        //     $totalAmount = $items->sum('total_price');

        //     // Update total_amount di Purchase Order
        //     $po->update(['total_amount' => $totalAmount]);
        // });

        Supplier::factory()->count(10)->create();

        // $this->call([
        //     PoApprovalSeeder::class,
        // ]);
    }
}
