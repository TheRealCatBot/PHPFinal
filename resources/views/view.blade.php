<x-app-layout>
    <div class="quiz-detail-container">
        <div class="back-to-quizzes">
            <a href="/quizzes" class="back-link">← Back to Quizzes</a>
        </div>
        <div class="quiz-detail-card">
            <div class="quiz-detail-header">
                <img src="{{ asset($quiz->photo ? 'photos/' . $quiz->photo : 'photos/default-image.jpg') }}" alt="{{ $quiz->name }}" class="quiz-detail-image">
                <h2 class="quiz-title">{{ $quiz['name'] }}</h2>
            </div>
            <div class="quiz-detail-body">
                <div class="quiz-info">
                    <p><strong>Status:</strong> {{ $quiz['status'] }}</p>
                    <p><strong>Description:</strong> {{ $quiz['description'] }}</p>
                    <p><strong>Author:</strong> {{ $quiz->user->name }}</p>
                    <p><strong>Questions:</strong> {{ $quiz->questions()->count() }}</p>
                </div>
                <div class="start-quiz-button">
                    <a href="{{ route('quiz.start', $quiz->id) }}" class="quiz-start-button">Start Quiz</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .quiz-detail-container {
        max-width: 700px;
        margin: 20px auto;
        padding: 20px;
        background: #f7f7f7;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .back-to-quizzes {
        margin-bottom: 20px;
    }

    .back-link {
        text-decoration: none;
        color: #4285f4;
        font-weight: bold;
    }

    .quiz-detail-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .quiz-detail-header {
        position: relative;
        text-align: center;
    }

    .quiz-detail-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .quiz-title {
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 10px 20px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .quiz-detail-body {
        padding: 40px 20px 20px;
    }

    .quiz-info p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 10px;
    }

    .quiz-start-button {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s;
    }

    .quiz-start-button:hover {
        background-color: #388E3C;
    }
</style>