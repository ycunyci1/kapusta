<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function imeges(): HasMany
    {
        return $this->hasMany(ExpenseImage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getIconAttribute()
    {
        return $this->category->icon;
    }
    public function getNameAttribute()
    {
        return $this->category->name;
    }
}
