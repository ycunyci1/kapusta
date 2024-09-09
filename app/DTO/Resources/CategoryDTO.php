<?php

namespace App\DTO\Resources;

use App\Models\Project;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="Category",
 *     description="Информация о категории"
 * )
 */
class CategoryDTO extends Data
{
    /**
     * @var string
     *
     * @OA\Property (
     *     format="string",
     *     example="food"
     * )
     */
    public string $name;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="15"
     * )
     */
    public int $purchaseCount;

    /**
     * @var string
     *
     * @OA\Property (
     *     format="string",
     *     example="https://example.com/path/to/file.jpg"
     * )
     */
    public string $icon;

    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="150.00"
     * )
     */
    public float $totalExpenses;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="20"
     * )
     */
    public int $personOfBudget;

    public function __construct(
        string $name,
        array $expenses,
        string $icon,
    )
    {
        $project = $expenses->first()->project;
        $this->name = $name;
        $this->totalExpenses = (float) $expenses->pluck('price')->sum;;
        $this->icon = $icon;
        $this->purchaseCount = $expenses->count();

        $this->personOfBudget = (int) round(($expenses->sum('price') / $project->budget) * 100);
    }
}
