<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'budget_department_id',
        'pr_number',
        'pr_date',
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

    // Relasi ke PurchaseRequisitionItem
    public function items()
    {
        return $this->hasMany(PurchaseRequisitionItem::class, 'pr_id');
    }
}
