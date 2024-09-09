<?php

namespace App\Http\Controllers;

use App\DTO\Requests\ProjectRequestDTO;
use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use App\DTO\Resources\ProjectDTO;
use App\DTO\Resources\ProjectListDTO;
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
            $expenses = array_map(function($expense) use ($project) {
                $expense['project_id'] = $project->id;
                $expense['category_id'] = $expense['categoryId'];
                $expense['account_id'] = $expense['accountId'];
                return $expense;
            }, $projectDTO->newExpenses);
            foreach ($expenses as $expense) {
                Expense::query()->create($expense);
            }
        }

        //старые expenses
        if ($projectDTO->oldExpenses) {
            $project->expenses()->attach($projectDTO->oldExpenses);
        }

        $expenses = $project->expenses;
        $totalExpenses = array_sum($expenses->pluck('price')->toArray());
        return $this->responseJson(new ProjectDTO(
            id: $project->id,
            totalBalance: $project->budget - $totalExpenses,
            name: $project->name,
            expenses: $totalExpenses,
            limits: new LimitDTO(
                spent: $totalExpenses,
                limit: $project->budget
            ),
            categories: CategoryDTO::collect(
                Category::query()->whereHas('expenses', fn($expense) => $expense->where('project_id', $project->id))->get()
                    ->load(['expenses' => fn($query) => $query->where('project_id', $project->id)])
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
     * @return ProjectDTO
     */

    public function show(int $projectId)
    {
        $project = Project::query()->find($projectId);
        $expenses = $project->expenses;
        $totalExpenses = array_sum($expenses->pluck('price')->toArray());
        return new ProjectDTO(
            id: $projectId,
            totalBalance: $project->budget - $totalExpenses,
            name: $project->name,
            expenses: $totalExpenses,
            limits: new LimitDTO(
                spent: $totalExpenses,
                limit: $project->budget
            ),
            categories: CategoryDTO::collect(
                Category::query()->whereHas('expenses', fn($expense) => $expense->where('project_id', $project->id))->get()->load(['expenses' => fn($query) => $query->whereHas('project', fn($project) => $project->where('id', $projectId))->select('id', 'price', 'project_id')])
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $projectId)
    {
//        $project = Project::query()->find($projectId);
//        try {
//            $projectDTO = ProjectRequestDTO::from($request->all());
//        } catch (Exception $e) {
//            return $this->responseValidationError($e->getMessage());
//        }
//        Project::query()->where('id', $projectId)->update($projectDTO->toArray());

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
