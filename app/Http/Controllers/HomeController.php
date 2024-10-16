<?php

namespace App\Http\Controllers;

use App\DTO\Resources\DiagramExpenseDetailDTO;
use App\DTO\Resources\DiagramExpenseDTO;
use App\DTO\Resources\HomeLimitDTO;
use App\DTO\Resources\HomePageCategoryDTO;
use App\DTO\Resources\HomePageExpenseDetailDTO;
use App\DTO\Resources\HomePageExpensesDTO;
use App\DTO\Resources\HomePageDTO;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/home",
     *     summary="Получить информацию для главной страницы",
     *     tags={"Home"},
     *     @OA\Parameter(
     *          name="period",
     *          description="Период для затрат",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string", format="date"),
     *              example={"2024-09-24", "2024-10-16"}
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Данные для главной страницы",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/HomePageData")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Пользователь не авторизован",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Unauthorized"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверный запрос",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Invalid request"
     *              )
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $period = $request->get('period');
        $user = auth()->user();
        $projects = $user->projects;
        $allExpenses = $projects->map(fn($project) => $project->expenses)->flatten()->values();

        $dayExpenses = $allExpenses->where('date', now()->format('Y-m-d'));
        $dayDiagramExpenseData = collect();
        foreach ($dayExpenses->groupBy('category_id') as $categoryId => $categoryExpenses) {
            $category = Category::query()->find($categoryId);
            $dayDiagramExpenseData->push(new DiagramExpenseDetailDTO(
                name: $category->name,
                value: $categoryExpenses->pluck('price')->sum(),
                color: $category->color,
            ));
        }
        $dayCategories = $dayExpenses->map(fn($expense) => $expense->category)->unique('id');
        $dayCategories = $dayCategories->map(function ($category) use ($dayExpenses, $allExpenses) {
            $maxValue = $allExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $currentValue = $dayExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $category->comment = $dayExpenses->where('category_id', $category->id)->count() . ' purchases';
            $category->max_value = $maxValue;
            $category->current_value = $currentValue;

            //todo: написать логику когда будет известно
            $category->limit_percent = rand(1, 90);

            $category->max_percent = $currentValue / $maxValue * 100;
            return HomePageCategoryDTO::from($category);
        });


        $weekExpenses = $allExpenses->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        $weekDiagramExpenseData = collect();
        foreach ($weekExpenses->groupBy('category_id') as $categoryId => $categoryExpenses) {
            $category = Category::query()->find($categoryId);
            $weekDiagramExpenseData->push(new DiagramExpenseDetailDTO(
                name: $category->name,
                value: $categoryExpenses->pluck('price')->sum(),
                color: $category->color,
            ));
        }
        $weekCategories = $weekExpenses->map(fn($expense) => $expense->category)->unique('id');
        $weekCategories = $weekCategories->map(function ($category) use ($weekExpenses, $allExpenses) {
            $maxValue = $allExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $currentValue = $weekExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $category->comment = $weekExpenses->where('category_id', $category->id)->count() . ' purchases';
            $category->max_value = $maxValue;
            $category->current_value = $currentValue;

            //todo: написать логику когда будет известно
            $category->limit_percent = rand(1, 90);

            $category->max_percent = $currentValue / $maxValue * 100;
            return HomePageCategoryDTO::from($category);
        });


        $monthExpenses = $allExpenses->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
        $monthDiagramExpenseData = collect();
        foreach ($monthExpenses->groupBy('category_id') as $categoryId => $categoryExpenses) {
            $category = Category::query()->find($categoryId);
            $monthDiagramExpenseData->push(new DiagramExpenseDetailDTO(
                name: $category->name,
                value: $categoryExpenses->pluck('price')->sum(),
                color: $category->color,
            ));
        }
        $monthCategories = $monthExpenses->map(fn($expense) => $expense->category)->unique('id');
        $monthCategories = $monthCategories->map(function ($category) use ($monthExpenses, $allExpenses) {
            $maxValue = $allExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $currentValue = $monthExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $category->comment = $monthExpenses->where('category_id', $category->id)->count() . ' purchases';
            $category->max_value = $maxValue;
            $category->current_value = $currentValue;

            //todo: написать логику когда будет известно
            $category->limit_percent = rand(1, 90);

            $category->max_percent = $currentValue / $maxValue * 100;
            return HomePageCategoryDTO::from($category);
        });


        $yearExpenses = $allExpenses->whereBetween('date', [now()->startOfYear(), now()->endOfYear()]);
        $yearDiagramExpenseData = collect();
        foreach ($yearExpenses->groupBy('category_id') as $categoryId => $categoryExpenses) {
            $category = Category::query()->find($categoryId);
            $yearDiagramExpenseData->push(new DiagramExpenseDetailDTO(
                name: $category->name,
                value: $categoryExpenses->pluck('price')->sum(),
                color: $category->color,
            ));
        }
        $yearCategories = $yearExpenses->map(fn($expense) => $expense->category)->unique('id');
        $yearCategories = $yearCategories->map(function ($category) use ($yearExpenses, $allExpenses) {
            $maxValue = $allExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $currentValue = $yearExpenses->where('category_id', $category->id)->pluck('price')->sum();
            $category->comment = $yearExpenses->where('category_id', $category->id)->count() . ' purchases';
            $category->max_value = $maxValue;
            $category->current_value = $currentValue;

            //todo: написать логику когда будет известно
            $category->limit_percent = rand(1, 90);

            $category->max_percent = $currentValue / $maxValue * 100;
            return HomePageCategoryDTO::from($category);
        });
        if ($period && gettype($period) === 'array' &&  count($period) === 2) {
            $periodExpenses = $allExpenses->whereBetween('date', $period);
            $periodDiagramExpenseData = collect();
            foreach ($periodExpenses->groupBy('category_id') as $categoryId => $categoryExpenses) {
                $category = Category::query()->find($categoryId);
                $periodDiagramExpenseData->push(new DiagramExpenseDetailDTO(
                    name: $category->name,
                    value: $categoryExpenses->pluck('price')->sum(),
                    color: $category->color,
                ));
            }
            $periodCategories = $periodExpenses->map(fn($expense) => $expense->category)->unique('id');
            $periodCategories = $periodCategories->map(function ($category) use ($periodExpenses, $allExpenses) {
                $maxValue = $allExpenses->where('category_id', $category->id)->pluck('price')->sum();
                $currentValue = $periodExpenses->where('category_id', $category->id)->pluck('price')->sum();
                $category->comment = $periodExpenses->where('category_id', $category->id)->count() . ' purchases';
                $category->max_value = $maxValue;
                $category->current_value = $currentValue;

                //todo: написать логику когда будет известно
                $category->limit_percent = rand(1, 90);

                $category->max_percent = $currentValue / $maxValue * 100;
                return HomePageCategoryDTO::from($category);
            });
        }
        return $this->responseJson(new HomePageDTO(
            totalBalance: $user->budget,
            expenses: new HomePageExpensesDTO(
                dayData: new HomePageExpenseDetailDTO(
                    diagramData: new DiagramExpenseDTO(
                        items: $dayDiagramExpenseData,
                        priceValue: $dayExpenses->pluck('price')->sum(),
                    ),
                    categories: $dayCategories
                ),
                weekData: new HomePageExpenseDetailDTO(
                    diagramData: new DiagramExpenseDTO(
                        items: $weekDiagramExpenseData,
                        priceValue: $weekExpenses->pluck('price')->sum(),
                    ),
                    categories: $weekCategories
                ),
                monthData: new HomePageExpenseDetailDTO(
                    diagramData: new DiagramExpenseDTO(
                        items: $monthDiagramExpenseData,
                        priceValue: $monthExpenses->pluck('price')->sum(),
                    ),
                    categories: $monthCategories
                ),
                yearData: new HomePageExpenseDetailDTO(
                    diagramData: new DiagramExpenseDTO(
                        items: $yearDiagramExpenseData,
                        priceValue: $yearExpenses->pluck('price')->sum(),
                    ),
                    categories: $yearCategories
                ),
                periodData: $period && gettype($period) === 'array' && count($period) === 2
                    ? new HomePageExpenseDetailDTO(
                        diagramData: new DiagramExpenseDTO(
                            items: $periodDiagramExpenseData,
                            priceValue: $periodExpenses->pluck('price')->sum(),
                        ),
                        categories: $periodCategories
                    ) : null,
            ),
            limits: new HomeLimitDTO(
                currentValue: $monthExpenses->pluck('price')->sum(),
                maxValue: $user->budget,
                month: now()->month,
            ),
        ));
    }
}
