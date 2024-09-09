<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use App\Models\Project;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="HistoryExpenses",
 *     description="Информация о затратах в истории"
 * )
 */
class HistoryExpensesDTO extends Data
{


    public function __construct(
        /**
         * @var int
         *
         * @OA\Property (
         *     format="int",
         *     example="1"
         * )
         */
        public int    $id,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="food"
         * )
         */
        public string $name,

        /**
         * @var string|null
         *
         * @OA\Property (
         *     format="string",
         *     example="cool"
         * )
         */
        public ?string $comment,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="200.00"
         * )
         */
        public float $price,

        /**
         * @var int
         *
         * @OA\Property (
         *     format="int",
         *     example="5"
         * )
         */
        public int $categoryId,

        /**
         * @var CurrencyUnit
         *
         * @OA\Property (
         *     format="string",
         *     example="$",
         *     enum={"$", "€", "£", "₽"}
         * )
         */
        public CurrencyUnit $unit = CurrencyUnit::USD,
    )
    {

    }
}
