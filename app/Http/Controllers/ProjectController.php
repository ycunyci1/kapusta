<?php

namespace App\Http\Controllers;

use App\DTO\CategoryDTO;
use App\DTO\LimitDTO;
use App\DTO\ProjectDTO;
use App\DTO\ProjectListDTO;
use App\Models\Project;
use Illuminate\Http\Request;

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
