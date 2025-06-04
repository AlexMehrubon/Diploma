<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\DifficultyLevel;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'duration' => $this->faker->randomFloat(2, 1, 100),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'status_id' => Status::inRandomOrder()->first()->id,
            'difficulty_level_id' => DifficultyLevel::inRandomOrder()->first()->id,
        ];
    }
}
