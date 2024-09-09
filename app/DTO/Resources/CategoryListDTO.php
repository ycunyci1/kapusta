<?php

namespace App\DTO\Resources;

use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="CategoryList",
 *     description="Список проектов"
 * )
 */
class CategoryListDTO extends Data
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
         *     example="https://example.com/path/to/file"
         * )
         */
        public string $icon,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="Taxes"
         * )
         */
        public string $name,
    )
    {
    }
}
