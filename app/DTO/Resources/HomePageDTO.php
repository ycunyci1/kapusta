<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="HomePageData",
 *     description="Информация для главной страницы"
 * )
 */
class HomePageDTO extends Data
{
    public function __construct(
        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public float $totalBalance,

        /**
         * @var array
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpensesData"
         * )
         */
        public HomePageExpensesDTO $expenses,

        /**
         * @var HomeLimitDTO
         * @OA\Property(ref="#/components/schemas/HomeLimits")
         */
        public HomeLimitDTO $limits,

        /**
         * @var array|null
         * @OA\Property (
         *     format="array",
         *     @OA\Items(type = "string")
         * )
         */
        public ?DataCollection $incomes = null,

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
