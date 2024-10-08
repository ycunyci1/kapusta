<?php

namespace App\DTO\Requests;

use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use App\Models\Expense;
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
     * @var mixed
     *
     * @OA\Property (
     *    type="array",
     *      @OA\Items(type="integer"),
     *     example=[1,2,3]
     * )
     */
    public mixed $oldExpenses;

    /**
     * @var array|null
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/ExpenceRequest")
     * )
     */
    #[DataCollectionOf(ExpenseRequestDTO::class)]
    public ?iterable $newExpenses;

    /**
     * @throws \Exception
     */
    public function __construct(
        ?float  $budget = null,
        ?string $name = null,
        ?array $oldExpenses = null,
        ?array $newExpenses = null,
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

        if ($oldExpenses) {
            $oldExpenses = array_map(fn($oldExpense) => Expense::find($oldExpense)?->toArray(),$oldExpenses);
            $oldExpenses = array_filter($oldExpenses, fn($oldExpense) => $oldExpense !== null);
            $this->oldExpenses = ExpenseRequestDTO::collect($oldExpenses);
        }
        $this->newExpenses = ExpenseRequestDTO::collect($newExpenses);
    }
}
