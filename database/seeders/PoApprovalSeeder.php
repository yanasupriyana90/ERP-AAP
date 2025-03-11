<?php

namespace Database\Seeders;

use App\Models\PoApproval;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseOrder = PurchaseOrder::first(); // Ambil PO pertama

        // Tambah approval untuk Manager (level 1)
        PoApproval::create([
            'po_id' => $purchaseOrder->id,
            'user_id' => User::where('role', 'Manager')->first()->id,
            'level' => 1,
            'status' => 0,
            'notes' => null,
        ]);

        // Tambah approval untuk Direktur (level 2)
        PoApproval::create([
            'po_id' => $purchaseOrder->id,
            'user_id' => User::where('role', 'Direktur')->first()->id,
            'level' => 2,
            'status' => 0,
            'notes' => null,
        ]);
    }
}
