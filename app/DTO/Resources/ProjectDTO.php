<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="ProjectDetail",
 *     description="Детальная информация проекта"
 * )
 */
class ProjectDTO extends Data
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
        public int                      $id,
        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public float                    $totalBalance,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="Car"
         * )
         */
        public string                   $name,

        /**
         * @var array
         * @OA\Property (
         *  ref="#/components/schemas/ProjectDetailExpenses"
         * )
         */
        public ProjectDetailExpensesDTO $expenses,

        /**
         * @var LimitDTO
         * @OA\Property(ref="#/components/schemas/ProjectLimits")
         */
        public LimitDTO                 $limits,

        /**
         * @var array
         * @OA\Property (
         *     format="array",
         *     @OA\Items(ref="#/components/schemas/Category")
         * )
         */
        #[DataCollectionOf(CategoryDTO::class)]
        public iterable                 $categories,

        /**
         * @var array|null
         * @OA\Property (
         *     format="array",
         *     @OA\Items(type = "string")
         * )
         */
        public ?DataCollection          $incomes = null,

        /**
         * @var CurrencyUnit
         *
         * @OA\Property (
         *     format="string",
         *     example="$",
         *     enum={"$", "€", "£", "₽"}
         * )
         */
        public CurrencyUnit             $unit = CurrencyUnit::USD,
    )
    {
    }
}
