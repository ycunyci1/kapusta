<?php

namespace App\DTO\Resources;

use App\Enums\CurrencyUnit;
use Spatie\LaravelData\Data;

/**
 * @OA\Schema(
 *     schema="ProjectLimits",
 *     description="Информация о лимитах"
 * )
 */
class LimitDTO extends Data
{
    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="500.00"
     * )
     */
    public float $spent;

    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="1000.00"
     * )
     */
    public float $limit;

    /**
     * @var integer
     *
     * @OA\Property (
     *     format="integer",
     *     example="50"
     * )
     */
    public int $percent;

    /**
     * @var CurrencyUnit
     *
     * @OA\Property (
     *     format="string",
     *     example="$",
     *     enum={"$", "€", "£", "₽"}
     * )
     */
    public CurrencyUnit $unit;

    public function __construct(
        float $spent,
        float $limit,
        CurrencyUnit $unit = CurrencyUnit::USD
    )
    {
        $this->spent = $spent;
        $this->limit = $limit;
        $this->percent = (int) round(($spent / $limit) * 100);
        $this->unit = $unit;
    }
}
