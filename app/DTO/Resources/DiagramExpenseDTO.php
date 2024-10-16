<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="DiagramExpenseData",
 *     description="Данные для диаграммы"
 * )
 */
class DiagramExpenseDTO extends Data
{
    public function __construct(

        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/DiagramExpenseDetailData")
         * )
         */
        #[DataCollectionOf(DiagramExpenseDetailDTO::class)]
        public iterable $items,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public float $priceValue,
    )
    {
    }
}
