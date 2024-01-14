<x-app-layout>
    <div class="quiz-container">
        @foreach($quizzes as $quiz)
            <div class="quiz-card">
                <div class="quiz-image">
                    <img src="{{ asset($quiz->photo ? 'photos/' . $quiz->photo : 'photos/default-image.jpg') }}" alt="{{ $quiz->name }}">
                </div>
                <div class="quiz-content">
                    <h3 class="quiz-title">{{ $quiz['name'] }}</h3>
                    <span class="quiz-status">Status: {{ ucfirst($quiz['status']) }}</span>
                    <a href="{{ route('quiz.view', $quiz->id) }}" class="quiz-button">View Quiz</a>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .quiz-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .quiz-card {
            flex: 1;
            min-width: 380px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .quiz-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .quiz-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .quiz-content {
            padding: 15px;
            text-align: center;
        }

        .quiz-title {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 10px;
        }

        .quiz-status {
            display: block;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .quiz-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4285f4;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .quiz-button:hover {
            background-color: #3367d6;
        }
    </style>
</x-app-layout>