<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="ExpenseList",
 *     description="Список затрат"
 * )
 */
class ExpenseListDTO extends Data
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
         *     example="https://example.com/path/to/file"
         * )
         */
        public string $icon,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="Taxes"
         * )
         */
        public string $name,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="1999-01-01"
         * )
         */
        public string $date,

        /**
         * @var string|null
         *
         * @OA\Property (
         *     format="string",
         *     example="Comment"
         * )
         */
        public ?string $comment,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1900.00"
         * )
         */
        public float $price,

        /**
         * @var CurrencyUnit
         *
         * @OA\Property (
         *     format="string",
         *     example="$",
         *     enum={"$", "€", "£", "₽"}
         * )
         */
        public CurrencyUnit $unit,
    )
    {
        if (!isset($this->unit)) {
            $this->unit = CurrencyUnit::USD;
        }
    }
}
