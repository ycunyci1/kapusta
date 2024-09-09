<?php

namespace App\DTO\Resources;

use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="AccountList",
 *     description="Список аккаунтов"
 * )
 */
class AccountListDTO extends Data
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
         *     example="Cash"
         * )
         */
        public string $name,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="cash"
         * )
         */
        public string $slug,
    )
    {
    }
}
