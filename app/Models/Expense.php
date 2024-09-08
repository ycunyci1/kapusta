<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function imeges(): HasMany
    {
        return $this->hasMany(ExpenseImage::class);
    }
}
