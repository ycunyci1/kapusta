<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Expense[]|Collection $expenses
 * @property float $budget
 * @property float $spent
 * @property string $name
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getSpentAttribute(): float
    {
        return $this->expenses()->sum('price');
    }

}
