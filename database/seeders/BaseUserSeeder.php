<?php

namespace Database\Seeders;

use App\Models\BaseUser;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseAccount;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BaseUser::create([
            'user_id' => 1,
        ]);
    }
}
