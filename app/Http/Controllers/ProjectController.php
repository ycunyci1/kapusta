<?php

namespace App\Http\Controllers;

use App\DTO\Requests\ProjectRequestDTO;
use App\DTO\Requests\ProjectUpdateDTO;
use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use App\DTO\Resources\ProjectDetailExpensesDetailDTO;
use App\DTO\Resources\ProjectDetailExpensesDTO;
use App\DTO\Resources\ProjectDTO;
use App\DTO\Resources\ProjectListDTO;
use App\Enums\PeriodEnum;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Exception;

class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/projects",
     *     summary="Получить список проектов пользователя",
     *     tags={"Projects"},
     *     @OA\Response(
     *          response=200,
     *          description="Список проектов",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ProjectList")
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
     * @return JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects;
        return $this->responseJson(ProjectListDTO::collect($projects));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/projects",
     *     summary="Создать новый проект",
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProjectRequest")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Проект успешно создан",
     *          @OA\JsonContent(ref="#/components/schemas/ProjectDetail")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Ошибка валидации данных",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Validation error"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Internal server error"
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
    public function store(Request $request)
    {
        try {
            $projectDTO = ProjectRequestDTO::from($request->all());
            $projectData = $projectDTO->toArray();
            $projectData['user_id'] = 1;
        } catch (\Exception $e) {
            return $this->responseValidationError($e->getMessage());
        }
        $project = Project::query()->create($projectData);
        //новые expenses
        if ($projectDTO->newExpenses) {
            $newExpenses = array_map(function ($expense) use ($project) {
                $expense['project_id'] = $project->id;
                return $expense;
            }, $projectDTO->newExpenses);
            foreach ($newExpenses as $expense) {
                $expense = Expense::query()->create($expense);
                $expense->projects()->attach($project->id);
            }
        }

        if ($projectDTO->oldExpenses) {
            $project->expenses()->attach($projectDTO->oldExpenses);
        }

        $expenses = $project->expenses;
        $projectCategories = Category::query()->whereHas('expenses', fn($expensesQuery) => $expensesQuery->whereIn('expenses.id', $expenses->pluck('id')
            ->toArray()))->get()->load(['expenses' => fn($query) => $query->whereHas('projects', fn($projects) => $projects
            ->where('projects.id', $project->id))]);
        $projectCategories = $projectCategories->map(function ($projectCategory) use ($project) {
            $projectCategory->project_id = $project->id;
            return $projectCategory;
        });
        $totalExpenses = array_sum($expenses->pluck('price')->toArray());
        return $this->responseJson(new ProjectDTO(
            id: $project->id,
            totalBalance: $project->budget - $totalExpenses,
            name: $project->name,
            expenses: new ProjectDetailExpensesDTO(
                expenses: ProjectDetailExpensesDetailDTO::collect($expenses),
                total: (float)array_sum($expenses->pluck('price')->toArray())
            ),
            limits: new LimitDTO(
                spent: $totalExpenses,
                limit: $project->budget
            ),
            categories: CategoryDTO::collect(
                $projectCategories
            )
        ), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/projects/{projectId}",
     *     summary="Получить информацию о проекте",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *          name="projectId",
     *          description="Id проекта",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="period",
     *          description="Период для затрат",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              example="day|week|month|year"
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Информация о проекте",
     *          @OA\JsonContent(ref="#/components/schemas/ProjectDetail")
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
     * @param int $projectId
     * @param Request $request
     * @return ProjectDTO
     */

    public function show(int $projectId, Request $request)
    {

        $project = Project::query()->find($projectId);
        $expenses = $project->expenses;
        $period = $request->get('period');
        $expensesForGraph = collect();
        if ($period) {
            $expensesForGraph = match ($period) {
                PeriodEnum::DAY->value => $expenses->where('date', now()->format('Y-m-d')),
                PeriodEnum::WEEK->value => $expenses->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
                PeriodEnum::MONTH->value => $expenses->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]),
                PeriodEnum::YEAR->value => $expenses->whereBetween('date', [now()->startOfYear(), now()->endOfYear()]),
            };
        }

        $projectCategories = Category::query()->whereHas('expenses', fn($expensesQuery) => $expensesQuery->whereIn('expenses.id', $expenses->pluck('id')
            ->toArray()))->get()->load(['expenses' => fn($query) => $query->whereHas('projects', fn($projects) => $projects
            ->where('projects.id', $projectId))]);
        $projectCategories = $projectCategories->map(function ($projectCategory) use ($projectId) {
            $projectCategory->project_id = $projectId;
            return $projectCategory;
        });

        $totalExpenses = array_sum($expenses->pluck('price')->toArray());
        return new ProjectDTO(
            id: $projectId,
            totalBalance: $project->budget - $totalExpenses,
            name: $project->name,
            expenses: new ProjectDetailExpensesDTO(
                expenses: ProjectDetailExpensesDetailDTO::collect($expensesForGraph),
                total: (float)array_sum($expensesForGraph->pluck('price')->toArray())
            ),
            limits: new LimitDTO(
                spent: $totalExpenses,
                limit: $project->budget
            ),
            categories: CategoryDTO::collect(
                $projectCategories
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $projectId)
    {
        $project = Project::find($projectId);
        try {
            $projectDTO = ProjectUpdateDTO::from($request->all());
        } catch (Exception $e) {
            return $this->responseValidationError($e->getMessage());
        }
        $project->update($projectDTO->toArray());

        return $this->responseJson([], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/projects/{projectId}",
     *     summary="Удалить проект",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *          name="projectId",
     *          description="Id проекта",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="Проект успешно удален"
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
     * @param int $projectId
     * @return JsonResponse
     */
    public function destroy(int $projectId)
    {
        Project::query()->where('id', $projectId)->delete();
        return $this->responseJson([], 204);
    }
}
