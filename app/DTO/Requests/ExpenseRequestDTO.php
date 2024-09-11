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
    public ?int $projectId;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="1"
     * )
     */
    public int $categoryId;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="1"
     * )
     */
    public int $accountId;


    /**
     * @throws \Exception
     */
    public function __construct(
        ?float  $price = null,
        ?string $date = null,
        ?string $comment = null,
        ?int    $projectId = null,
        ?int    $categoryId = null,
        ?int    $accountId = null,
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
        if (!$categoryId) {
            throw new \Exception("Category is required");
        }
        $this->categoryId = $categoryId;
        if (!$accountId) {
            throw new \Exception("Account is required");
        }
        $this->accountId = $accountId;

        $this->projectId = $projectId;

        $this->comment = $comment;
    }

    public function toSnakeCaseArray(): array
    {
        return collect($this->toArray())
            ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }
}
