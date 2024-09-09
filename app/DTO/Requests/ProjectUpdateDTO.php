<?php

namespace App\DTO\Requests;

use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @OA\Schema(
 *     schema="ProjectUpdateRequest",
 *     description="Данные для обновления проекта"
 * )
 */
class ProjectUpdateDTO extends Data
{

    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="1000.00"
     * )
     */
    public float $budget;

    /**
     * @var string
     *
     * @OA\Property (
     *     format="string",
     *     example="Car"
     * )
     */
    public string $name;

    /**
     * @throws \Exception
     */
    public function __construct(
        ?float  $budget = null,
        ?string $name = null,
    )
    {
        if (!$budget) {
            throw new \Exception("Budget can't be null");
        }
        $this->budget = $budget;
        if (!$name) {
            throw new \Exception("Name can't be null");
        }
        $this->name = $name;
    }
}
