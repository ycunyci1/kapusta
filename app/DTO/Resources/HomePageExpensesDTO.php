<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="HomeExpensesData",
 *     description="Информация об expenses"
 * )
 */
class HomePageExpensesDTO extends Data
{
    public function __construct(
        /**
         * @var HomePageExpenseDetailDTO
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpenseDetailData"
         * )
         */
        public HomePageExpenseDetailDTO $dayData,

        /**
         * @var HomePageExpenseDetailDTO
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpenseDetailData"
         * )
         */
        public HomePageExpenseDetailDTO $weekData,

        /**
         * @var HomePageExpenseDetailDTO
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpenseDetailData"
         * )
         */
        public HomePageExpenseDetailDTO $monthData,

        /**
         * @var HomePageExpenseDetailDTO
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpenseDetailData"
         * )
         */
        public HomePageExpenseDetailDTO $yearData,

        /**
         * @var HomePageExpenseDetailDTO|null
         * @OA\Property (
         *  ref="#/components/schemas/HomeExpenseDetailData"
         * )
         */
        public ?HomePageExpenseDetailDTO $periodData = null,
    )
    {
    }
}
