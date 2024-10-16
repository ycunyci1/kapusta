<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="HomeExpenseDetailData",
 *     description="Детальная информация об expense"
 * )
 */
class HomePageExpenseDetailDTO extends Data
{
    public function __construct(
        /**
         * @var array
         * @OA\Property (
         *  ref="#/components/schemas/DiagramExpenseData"
         * )
         */
        public DiagramExpenseDTO $diagramData,

        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/HomePageCategoryData")
         * )
         */
        #[DataCollectionOf(HomePageCategoryDTO::class)]
        public iterable $categories,

    )
    {
    }
}
