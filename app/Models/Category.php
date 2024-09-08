<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property null|string $icon
 * @property Project $project
 * @property Expense[]|Collection $expenses
 */
class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    /**
     * @return BelongsToMany
     */
    public function expenses(): BelongsToMany
    {
        return $this->belongsToMany(Expense::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
