<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'user_id',
        'level',   // Level approval: 1 = Manager, 2 = Direktur
        'status',  // 0 = Pending, 1 = Approved, 2 = Rejected
        'notes'
    ];

    /**
     * Relasi ke Purchase Order
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    /**
     * Relasi ke User (Approver)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
