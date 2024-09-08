<?php

namespace App\Http\Controllers;

use App\DTO\Requests\ProjectRequestDTO;
use App\DTO\Resources\CategoryDTO;
use App\DTO\Resources\LimitDTO;
use App\DTO\Resources\ProjectDTO;
use App\DTO\Resources\ProjectListDTO;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Exception;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects;
        return ProjectListDTO::collect($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $projectDTO = ProjectRequestDTO::from($request->all());
        } catch (Exception $e) {
            return $this->responseValidationError($e->getMessage());
        }
        $project = Project::query()->create($projectDTO->toArray());

        $expenses = $projectDTO->expenses
            ->map(function ($expense) use ($project) {
                $expense->projectId = $project->id;
                return $expense;
            });

        foreach ($expenses as $expense) {
            Expense::query()->create($expense->toArray());
        }

        return $this->responseJson($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $projectId)
    {
        $project = Project::query()->find($projectId);
        $expenses = $project->expenses;

        $totalExpenses = array_sum($expenses->pluck('price')->toArray());
        return new ProjectDTO(
            totalBalance: $project->budget - $totalExpenses,
            name: $project->name,
            expenses: $totalExpenses,
            limits: new LimitDTO(
                spent: $totalExpenses,
                limit: $project->budget
            ),
            categories: CategoryDTO::collect(
                $project->categories
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $projectId)
    {
        $project = Project::query()->find($projectId);
        try {
            $projectDTO = ProjectRequestDTO::from($request->all());
        } catch (Exception $e) {
            return $this->responseValidationError($e->getMessage());
        }
        Project::query()->where('id', $projectId)->update($projectDTO->toArray());

        return $this->responseJson($project, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $projectId)
    {
        Project::query()->where('id', $projectId)->delete();
        return $this->responseJson([], 204);
    }
}
