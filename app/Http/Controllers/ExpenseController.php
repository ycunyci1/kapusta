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
        $requestData['project_id'] = $projectId;
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
     *     summary="Удалить затрату из проекта",
     *     tags={"Projects"},
     *
     *     @OA\Parameter(
     *          name="projectId",
     *          description="Id проекта",
     *          in="path",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *
     *     @OA\Parameter(
     *          name="expenseId",
     *          description="Id затраты",
     *          in="path",
     *          required=true,
     *          example="1",
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
    public function destroy(int $projectId, int $expenseId)
    {
        $project = Project::query()->find($projectId);
        $expense = Expense::query()->find($expenseId);
        /** @var Expense $expense */
        /** @var Project $project */
        $project->expenses()->detach($expenseId);
        if (!$expense->projects()->count()) {
            $expense->delete();
        }
        return $this->responseJson();
    }
}
