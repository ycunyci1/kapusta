<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'icon' => $this->getRandomIcon(),
            'color' => fake()->hexColor,
        ];
    }

    private function getRandomIcon()
    {
        $imagesPath = public_path('icons');
        $images = glob($imagesPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (count($images) === 0) {
            return null;
        }
        $randomImage = $images[array_rand($images)];
        return str_replace(public_path(), '', $randomImage);
    }
}
