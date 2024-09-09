<?php

namespace App\DTO\Requests;

use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="ProjectRequest",
 *     description="Данные для создания проекта"
 * )
 */
class ProjectRequestDTO extends Data
{

    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="1000.00"
     * )
     */
    public float $budget;

    /**
     * @var string
     *
     * @OA\Property (
     *     format="string",
     *     example="Car"
     * )
     */
    public string $name;

    /**
     * @var array|null
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/ExpenceRequest")
     * )
     */
    #[DataCollectionOf(ExpenseRequestDTO::class)]
    public ?iterable $expenses;

    /**
     * @throws \Exception
     */
    public function __construct(
        ?float  $budget = null,
        ?string $name = null,
        ?array $expenses = null,
    )
    {
        if (!$budget) {
            throw new \Exception("Budget can't be null");
        }
        $this->budget = $budget;
        if (!$name) {
            throw new \Exception("Name can't be null");
        }
        $this->name = $name;
        if ($expenses) {
            try {
                $this->expenses = ExpenseRequestDTO::collect($expenses);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }
}
