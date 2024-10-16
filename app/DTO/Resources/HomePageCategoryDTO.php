<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="HomePageCategoryData",
 *     description="Информация для категорий главной страницы"
 * )
 */
class HomePageCategoryDTO extends Data
{
    public function __construct(
        /**
         * @var int
         *
         * @OA\Property (
         *     format="integer",
         *     example="1"
         * )
         */
        public int $id,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="/storage/path-to-image.jpg"
         * )
         */
        public string $icon,

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
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="10 purchase"
         * )
         */
        public string $comment,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="10000.00"
         * )
         */
        public int $maxValue,

        /**
         * @var float
         *
         * @OA\Property (
         *     format="float",
         *     example="1000.00"
         * )
         */
        public int $currentValue,

        //todo: Пока хз что за limitPercent
        /**
         * @var int
         *
         * @OA\Property (
         *     format="integer",
         *     example="5"
         * )
         */
        public int $limitPercent,

        /**
         * @var int
         *
         * @OA\Property (
         *     format="integer",
         *     example="10"
         * )
         */
        public int $maxPercent,

    )
    {
    }
}
