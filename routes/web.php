<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/quiz/{id?}', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::post('/quiz/{id?}', [QuizController::class, 'store']);
    Route::delete('/quiz/{id}', [QuizController::class, 'delete'])->name('quiz.delete');
    Route::get('/pending-quizzes', [QuizController::class, 'indexAdmin'])->name('pendingQuizzes');
    Route::get('/my-quizzes', [QuizController::class, 'myQuizzes'])->name('myQuizzes');
});

Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes');
Route::get('/quiz-view/{id}', [QuizController::class, 'show'])->name('quiz.view');
Route::get('/quizzing/{id}', [QuizController::class, 'quizzing'])->name('quiz.start');


Route::post('/check-answer', [QuizController::class, 'checkAnswer']);

Route::post('/subscribe', [QuizController::class, 'subscribe']);
require __DIR__.'/auth.php';
