<?php

namespace App\DTO\Resources;

use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="ProjectList",
 *     description="Список проектов"
 * )
 */
class ProjectListDTO extends Data
{

    /**
     * @var int
     *
     * @OA\Property (
     *     format="int",
     *     example="1"
     * )
     */
    public int $id;

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
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="600.00"
     * )
     */
    public float $spent;

    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="60000.00"
     * )
     */
    public float $budget;

    /**
     * @var int
     *
     * @OA\Property (
     *     format="int",
     *     example="50"
     * )
     */
    public int $percent;


    public function __construct(
        int $id,
        string $name,
        float $spent,
        float $budget,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->spent = $spent;
        $this->budget = $budget;
        $this->percent = (int) round(($spent / $budget) * 100);
    }
}
