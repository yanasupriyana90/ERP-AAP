<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'budget_department_id',
        'supplier_id',
        'po_number',
        'po_date',
        'total_amount',
        'status',
        'notes',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi ke BudgetDepartment
    public function budgetDepartment()
    {
        return $this->belongsTo(BudgetDepartment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke PurchaseOrderItem
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'po_id');
    }

    public function approvals()
    {
        return $this->hasMany(PoApproval::class, 'po_id');
    }
}
