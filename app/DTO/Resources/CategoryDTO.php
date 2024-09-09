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
     * @var int
     *
     * @OA\Property (
     *     format="int",
     *     example="1"
     * )
     */
    public int $id;

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

    public int $projectId;

    public ?iterable $expenses;

    public function __construct(
        string     $name,
        string     $icon,
        Collection $expenses,
        int        $projectId
    )
    {
        dd($projectId);

        $project = Project::find($projectId);
        $this->name = $name;
        $this->totalExpenses = (float)$expenses->sum('price');
        $this->icon = $icon;
        $this->purchaseCount = $expenses->count();
        $this->personOfBudget = (int)round(($expenses->sum('price') / $project->budget) * 100);
    }
}
