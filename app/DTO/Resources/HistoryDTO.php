<?php

namespace App\DTO\Resources;

use App\Models\Project;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="History",
 *     description="Информация об истории"
 * )
 */
class HistoryDTO extends Data
{


    public function __construct(
        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/CategoryHistory")
         * )
         */
        #[DataCollectionOf(HistoryCategoryDTO::class)]
        public iterable  $categories,

        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/HistoryExpenses")
         * )
         */
        #[DataCollectionOf(HistoryExpensesDTO::class)]
        public iterable  $expenses,
    )
    {
    }
}
