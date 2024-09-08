<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseAccount;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $project = Project::query()->create([
                'name' => fake()->word,
                'budget' => (float) rand(1000, 100000),
                'user_id' => fake()->randomElement(User::all()->pluck('id'))
            ]);
            foreach (Category::all() as $category) {
                $expenses = [];
                for ($a = 0; $a < rand(1, 15); $a++) {
                    $expense = Expense::query()->create([
                        'price' => (float) rand(10, 10000),
                        'date' => fake()->date,
                        'comment' => fake()->boolean ? fake()->text(50) : null,
                        'project_id' => $project->id,
                        'category_id' => $category->id,
                        'account_id' => fake()->randomElement(ExpenseAccount::all()->pluck('id')),
                    ]);
                    $expenses[] = $expense->id;
                }
                /** @var Category $category */
                $category->expenses()->attach($expenses);
            }
        }
    }
}
