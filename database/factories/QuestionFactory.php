<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $options = [
            fake()->word,
            fake()->word,
            fake()->word,
            fake()->word,
        ];

        $correctAnswer = fake()->randomElement($options);

        return [
            'quiz_id' => Quiz::factory(),
            'photo' => '',
            'question_text' => fake()->sentence,
            'correct_answer' => $correctAnswer,
            'options' => json_encode($options),
        ];
    }
}
