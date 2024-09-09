<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="ProjectDetailExpensesDetail",
 *     description="Детальная информация затрат проекта"
 * )
 */
class ProjectDetailExpensesDetailDTO extends Data
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
        public int          $id,
        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="car"
         * )
         */
        public string       $name,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="150.00"
         * )
         */
        public float        $price,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="1999-01-01"
         * )
         */

        public string       $date,

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
