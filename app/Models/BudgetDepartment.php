<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'code',
        'name',
        'amount',
        'used_amount',
        'remaining_amount',
        'valid_from',
        'valid_to',
        'status',
    ];

    /**
     * Relasi ke User (Pembuat Budget)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Accessor untuk menampilkan status dalam bentuk teks
     */
    public function getStatusTextAttribute()
    {
        return $this->status == 0 ? 'Aktif' : 'Nonaktif';
    }

    public function getValidFromFormattedAttribute()
    {
        return Carbon::parse($this->valid_from)->format('d-m-Y');
    }

    public function getValidToFormattedAttribute()
    {
        return Carbon::parse($this->valid_to)->format('d-m-Y');
    }

    public function getAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getUsedAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->used_amount, 0, ',', '.');
    }

    public function getRemainingAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }
}
