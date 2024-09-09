<?php

namespace App\DTO\Resources;

use App\Models\Project;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="CategoryHistory",
 *     description="Информация о категориях в истории"
 * )
 */
class HistoryCategoryDTO extends Data
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
        public int    $id,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="food"
         * )
         */
        public string $name,
    )
    {
    }
}
