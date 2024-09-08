<?php

namespace App\DTO;

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
    /**
     * @var float
     *
     * @OA\Property (
     *     format="float",
     *     example="1000.00"
     * )
     */
    public float $totalBalance;

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
     * @var DataCollection
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/Breadcrumb")
     * )
     */
    #[DataCollectionOf(BreadcrumbDTO::class)]
    public DataCollection $expenses;

    /**
     * @var DataCollection
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/Breadcrumb")
     * )
     */
    #[DataCollectionOf(BreadcrumbDTO::class)]
    public DataCollection $limits;

    /**
     * @var DataCollection
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/Breadcrumb")
     * )
     */
    #[DataCollectionOf(BreadcrumbDTO::class)]
    public DataCollection $incomes;

    /**
     * @var DataCollection
     * @OA\Property (
     *     format="array",
     *     @OA\Items(ref="#/components/schemas/Breadcrumb")
     * )
     */
    #[DataCollectionOf(BreadcrumbDTO::class)]
    public DataCollection $categories;

    public function __construct(
        float $totalBalance,
        string $name,
        iterable $expenses,
        iterable $limits,
        iterable $incomes,
        iterable $categories
    )
    {
    }
}
