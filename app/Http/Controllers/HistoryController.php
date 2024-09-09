<?php

namespace App\Http\Controllers;

use App\DTO\Resources\HistoryCategoryDTO;
use App\DTO\Resources\HistoryDTO;
use App\DTO\Resources\HistoryExpensesDTO;
use App\Http\Requests\Auth\CheckCodeRequest;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class HistoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/projects/history",
     *     summary="Получить историю",
     *     tags={"Projects"},
     *
     *     @OA\Parameter(
     *          name="q",
     *          description="Поиск",
     *          in="query",
     *          required=false,
     *          example="voluptatem",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="categories",
     *          description="Id категорий для фильтрации",
     *          in="query",
     *          required=false,
     *          example="[1,2,3]",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *              type="integer")
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="История",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/History"
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверный код",
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $requestCategories = $request->get('categories');
        $q = $request->get('q');

        $projectsIds = $user->projects->pluck('id')->toArray();

        $expensesQuery = Expense::query()
            ->whereHas('projects', function ($projects) use ($projectsIds) {
                $projects->whereIn('projects.id', $projectsIds);
            })->orderByDesc('date');

        if ($q) {
            $expensesQuery->where('comment', 'like', '%' . $q . '%');
        }

        if ($requestCategories) {
            $expensesQuery->whereIn('category_id', $requestCategories);
        }

        $expenses = $expensesQuery->get();
        $categories = Category::query()->whereHas('expenses', function ($expensesQuery) use ($expenses) {
            $expensesQuery->whereIn('expenses.id', $expenses->pluck('id')->toArray());
        })->select(['id', 'name'])->get();
        return $this->responseJson(new HistoryDTO(
            categories: HistoryCategoryDTO::collect($categories),
            expenses: HistoryExpensesDTO::collect($expenses)
        ));
    }
}
