<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
