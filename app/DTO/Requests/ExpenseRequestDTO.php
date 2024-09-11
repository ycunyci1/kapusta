<?php

namespace App\DTO\Requests;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="ExpenceRequest",
 *     description="Данные для затрат"
 * )
 */
class ExpenseRequestDTO extends Data
{
    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="500.00"
     * )
     */
    public float $price;

    /**
     * @var string
     *
     * @OA\Property (
     *     format="string",
     *     example="1999-01-01"
     * )
     */
    public string $date;

    /**
     * @var string|null
     *
     * @OA\Property (
     *     format="string",
     *     example="50"
     * )
     */
    public ?string $comment;

    /**
     * @var integer|null
     *
     * @OA\Property (
     *     format="integer",
     *     example="50"
     * )
     */
    public ?int $project_id;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="1"
     * )
     */
    public int $category_id;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="1"
     * )
     */
    public int $account_id;


    /**
     * @throws \Exception
     */
    public function __construct(
        ?float  $price = null,
        ?string $date = null,
        ?string $comment = null,
        ?int    $project_id = null,
        ?int    $category_id = null,
        ?int    $account_id = null,
    )
    {
        if (!$price) {
            throw new \Exception("Price is required");
        }
        $this->price = $price;
        if (!$date) {
            throw new \Exception("Date is required");
        }
        $this->date = $date;
        if (!$category_id) {
            throw new \Exception("Category is required");
        }
        $this->category_id = $category_id;
        if (!$account_id) {
            throw new \Exception("Account is required");
        }
        $this->account_id = $account_id;

        $this->project_id = $project_id;

        $this->comment = $comment;
    }

    public function toSnakeCaseArray(): array
    {
        return collect($this->toArray())
            ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }
}
