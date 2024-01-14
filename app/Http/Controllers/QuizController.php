<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class QuizController extends Controller
{
    public function index(): Factory|View|Application
    {

        $quizzes = Quiz::whereNotNull('photo')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
//            ->limit(8)
            ->get();

//        if ($quizzes->count() < 8) {
//            $additionalQuizzes = Quiz::whereNotNull('description')
//                ->whereNotIn('id', $quizzes->pluck('id'))
//                ->where('active', true)
//                ->orderBy('created_at', 'desc')
//                ->limit(8 - $quizzes->count())
//                ->get();
//
//            $quizzes = $quizzes->merge($additionalQuizzes);
//        }


        return view('home', ['quizzes' => $quizzes]);
    }

    public function indexAdmin(): Factory|View|Application
    {
        $quizzes = Quiz::where('status', 'pending')->get();

        return view('pendingQuizzes', ['quizzes' => $quizzes]);
    }

    public function show($id): Factory|View|Application
    {
        $quiz = Quiz::findOrFail($id);

        return view('view', compact('quiz'));
    }

    public function myQuizzes(): Factory|View|Application
    {
        $quizzes = Quiz::where('author_id', Auth::id())->get();

        return view('myQuizzes', compact('quizzes'));
    }

    public function edit($id = null): Factory|View|Application
    {
        $quiz = ($id) ? Quiz::find($id) : new Quiz;

        return view('edit', compact('quiz'));
    }

    public function store(Request $request, $id = null): Redirector|Application|RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'photo' => 'mimes:jpg,png,jpeg,webp|max:5048',
            'status' => ''
        ]);


        $quiz = ($id) ? Quiz::find($id) : new Quiz;

        $quiz->fill($data);

        if ($quiz->status == '' || Auth::user()->id != 1) {
            $quiz->status = 'pending';
        }

        if($request->photo) {
            $newPhotoName = time() . '-' . preg_replace('/[^A-Za-z0-9\-]/', '', $request->name) . '.' . $request->photo->extension();
            $request->photo->move(public_path('photos'), $newPhotoName);
            $quiz->photo = $newPhotoName;
        }

        $quiz->author_id = Auth::user()->id != 1 ? Auth::user()->id : $quiz->author_id;
        $quiz->save();

        if ($request->has('questions')) {

            foreach ($request->input('questions') as $key => $questionData) {

                if ($request->hasFile("questions.$key.question_photo")) {
                    $file = $request->file("questions.$key.question_photo");
                    $newPhotoName = time() . '-' . preg_replace('/[^A-Za-z0-9\-]/', '', $questionData['question_text']) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('photos'), $newPhotoName);
                    $questionPhotoPath = 'photos/' . $newPhotoName;
                } else {
                    $questionPhotoPath = null;
                }

                if (isset($questionData['id'])) {
                    $question = Question::find($questionData['id']);
                    if ($question) {
                        $question->update([
                            'question_text' => $questionData['question_text'],
                            'options' => json_encode(explode(",", $questionData['options'])),
                            'correct_answer' => $questionData['correct_answer'],
                            'order' => $questionData['order'],
                            'quiz_id' => $id,
                        ]);

                        if($questionPhotoPath) {
                            $question->photo = $questionPhotoPath;
                            $question->save();
                        }
                    }
                } else {
                    $quiz->questions()->create([
                        'question_text' => $questionData['question_text'],
                        'options' => json_encode(explode(",",$questionData['options'])),
                        'correct_answer' => $questionData['correct_answer'],
                        'quiz_id' => $id,
                        'order' => $questionData['order'],
                        'photo' => $questionPhotoPath
                    ]);
                }
            }
        }

        return redirect('/quizzes')->with('success', 'Quiz saved successfully!');
    }

    public function delete($id): Redirector|Application|RedirectResponse
    {
        $quiz = Quiz::findOrFail($id);
        if (Auth::id() == $quiz->author_id)
        {
            if ($quiz->photo) {
                $photoPath = 'photos/' . $quiz->photo;
                if (File::exists($photoPath)) {
                    File::delete($photoPath);
                }
            }

            $quiz->delete();

            return redirect('/my-quizzes')->with('success', 'Quiz deleted successfully.');
        }

        return redirect('/my-quizzes')->with('error', 'You are not authorized to delete this quiz.');
    }

    public function quizzing($id): Factory|View|Application
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('question', compact('quiz'));
    }


    public function checkAnswer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required',
        ]);


        $question = Question::find($data['question_id']);
        $isCorrect = $question && $question->correct_answer == $data['answer'];

        return response()->json(['correct' => $isCorrect]);
    }




    public function subscribe(): Redirector|Application|RedirectResponse
    {
        return redirect('/quizzes')->with('status', 'Subscribed successfully!');
    }
}
