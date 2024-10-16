<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="DiagramExpenseDetailData",
 *     description="Items для диаграммы"
 * )
 */
class DiagramExpenseDetailDTO extends Data
{
    public function __construct(
        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="Animals"
         * )
         */
        public string $name,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public float  $value,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="#ffffff"
         * )
         */
        public string $color,
    )
    {
    }
}
