<?php

namespace Database\Seeders;

use App\Models\ExpenseAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Cash',
                'slug' => 'cash',
                'icon' => fake()->imageUrl,
            ],
            [
                'name' => 'Card',
                'slug' => 'card',
                'icon' => fake()->imageUrl,
            ],
        ];
        foreach ($accounts as $account) {
            ExpenseAccount::query()->create($account);
        }
    }
}
