<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoApproval extends Model
{
    use HasFactory;

    protected $fillable = ['po_id', 'user_id', 'level', 'status', 'notes'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
