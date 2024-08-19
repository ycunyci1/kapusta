<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="User",
 *     description="Информация о пользователе"
 * )
 */
class UserDTO extends Data
{
    public function __construct(
        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="Александров Александр"
         * )
         */
        public string $name,

        /**
         * @var string
         *
         * @OA\Property (
         *     format="string",
         *     example="test@mail.ru"
         * )
         */
        public string $email
    )
    {
    }
}
