<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="ProjectDetailExpenses",
 *     description="Детальная информация затрат проекта"
 * )
 */
class ProjectDetailExpensesDTO extends Data
{


    public function __construct(
        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/ProjectDetailExpensesDetail")
         * )
         */
        #[DataCollectionOf(ProjectDetailExpensesDetailDTO::class)]
        public iterable     $expenses,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="120.00"
         * )
         */
        public float        $total = 0.0,

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
