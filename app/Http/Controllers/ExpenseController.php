<?php

namespace App\Http\Controllers;

use App\DTO\Requests\ExpenseRequestDTO;
use App\DTO\Resources\ExpenseListDTO;
use App\Http\Requests\Auth\CheckCodeRequest;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/projects/{projectId}/expenses",
     *     summary="Получить затраты по проекту",
     *     tags={"Projects"},
     *
     *     @OA\Parameter(
     *          name="projectId",
     *          description="Id проекта",
     *          in="path",
     *          required=true,
     *          example="126",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="categoryId",
     *          description="Id категории для фильтрации",
     *          in="query",
     *          required=false,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="month",
     *          description="Месяц за который надо прислать затраты(в разработке)",
     *          in="query",
     *          required=false,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Список затрат по проекту",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ExpenseList")
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
     * @param CheckCodeRequest $request
     * @return JsonResponse
     */
    public function index(Request $request, int $projectId)
    {
        $project = Project::query()->find($projectId);
        /** @var Project $project */
        $expenses = $project->expenses();
        if ($request->has('categoryId')) {
            $expenses = $expenses->where('category_id', $request->categoryId);
        }
        if ($request->has('month')) {
        }
        return $this->responseJson(ExpenseListDTO::collect($expenses->orderByDesc('date')));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/projects/{projectId}/expenses",
     *     summary="Добавить затрату в проект",
     *     tags={"Projects"},
     *
     *     @OA\Parameter(
     *          name="projectId",
     *          description="Id проекта",
     *          in="path",
     *          required=true,
     *          example="126",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ExpenceRequest")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Затрата успешно добавлена",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="Status"
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверные данные",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Invalid request data"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Проект не найден",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Project not found"
     *              )
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param Request $request
     * @param int $projectId
     * @return JsonResponse
     */
    public function store(Request $request, int $projectId)
    {
        $requestData = $request->all();
        $requestData['projectId'] = $projectId;
        $expenseDTO = ExpenseRequestDTO::from($requestData)->toSnakeCaseArray();

        $projectId = $expenseDTO['project_id'];
        unset($expenseDTO['project_id']);
        $expense = Expense::query()->create($expenseDTO);
        $expense->projects()->attach($projectId);
        return $this->responseJson();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/projects/{projectId}/expenses",
     *     summary="Добавить затрату в проект",
     *     tags={"Projects"},
     *
     *     @OA\Parameter(
     *          name="expenseId",
     *          description="Id затраты",
     *          in="path",
     *          required=true,
     *          example="126",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Затрата успешно удалена",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="Status"
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверные данные",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Invalid request data"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Проект не найден",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Project not found"
     *              )
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param int $expenseId
     * @return JsonResponse
     */
    public function destroy(int $expenseId)
    {
        Expense::query()->where('id', $expenseId)->delete();
        return $this->responseJson();
    }
}
