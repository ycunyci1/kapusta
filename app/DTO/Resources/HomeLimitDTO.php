<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="HomeLimits",
 *     description="Информация о лимитах для главной страницы"
 * )
 */
class HomeLimitDTO extends Data
{
    public function __construct(
        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="500.00"
         * )
         */
        public float $currentValue,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public float $maxValue,

        /**
         * @var int
         *
         * @OA\Property (
         *     format="integer",
         *     example="10"
         * )
         */
        public int   $month,
    )
    {
    }
}
