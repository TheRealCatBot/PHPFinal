<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quizzes = Quiz::all();
        $questions = Question::all();

        foreach ($quizzes as $quiz) {
            $randomQuestions = $questions->random(rand(5, 10));

            $quiz->questions()->attach($randomQuestions->pluck('id')->toArray());
        }
    }
}
