<x-app-layout>
    <div class="container mt-4">
        <form id="quizForm">
            @csrf
            @foreach($quiz->questions()->orderBy('order')->get() as $index => $question)
                <div class="card mb-3 question-card" id="question_card_{{ $index }}" data-index="{{ $index }}" style="{{ $index > 0 ? 'display:none;' : '' }}">
                    <div class="card-header">
                        Question {{ $index + 1 }}/{{ $quiz->questions->count() }}
                    </div>
                    <div class="card-body">
                        <img src="{{ asset($question->photo) }}">
                        <h5 class="card-title">{{ $question->question_text }}</h5>
                        <div class="mt-3">
                            @foreach(json_decode($question->options) as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" id="option_{{ $question->id }}_{{ $option }}">
                                    <label class="form-check-label" for="option_{{ $question->id }}_{{ $option }}">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                            <small id="feedback_{{ $question->id }}" class="form-text"></small>
                            <button type="button" class="btn btn-primary mt-2 submit-answer" onclick="checkAnswer({{ $question->id }}, {{ $index }})">Submit Answer</button>
                            @if($index < $quiz->questions->count() - 1)
                                <button type="button" class="btn btn-secondary mt-2 next-question" onclick="showNextQuestion({{ $index + 1 }})" style="display:none;">Next Question</button>
                            @else
                                <button type="button" id="final" class="btn btn-secondary mt-2 next-question" onclick="finish({{ $index + 1 }})" style="display:none;">Finish</button>
                            @endif

                            <button type="button" id="main_page_{{ $index + 1 }}" class="btn btn-secondary mt-2 next-question" onclick="window.location.href='/quizzes'" style="display:none;">Return to main page</button>

                        </div>
                    </div>
                </div>
            @endforeach
        </form>
        <div id="latest_score" style="display: none">
            Your Final Score:
        </div>
    </div>
    <script>

        let final = 0;

        function checkAnswer(questionId, index) {
            const selectedOption = document.querySelector(`input[name="answers[${questionId}]"]:checked`).value;
            const feedbackElement = document.getElementById('feedback_' + questionId);
            const nextButton = document.querySelector(`#question_card_${index} .next-question`);

            fetch('/check-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ question_id: questionId, answer: selectedOption })
            })
                .then(response => response.json())
                .then(data => {
                    const feedback = document.getElementById('feedback_' + questionId);

                    if (data.correct) {
                        final += 1;
                        feedbackElement.innerHTML = 'Correct!';
                        feedback.classList.add('text-success');
                        feedback.classList.remove('text-danger');
                    } else {
                        feedbackElement.innerHTML = 'Incorrect!';
                        feedback.classList.add('text-danger');
                        feedback.classList.remove('text-success');
                    }

                    // Disable the inputs and submit button
                    document.querySelectorAll(`#question_card_${index} .form-check-input`).forEach(input => input.disabled = true);
                    document.querySelector(`#question_card_${index} .submit-answer`).style.display = 'none';
                    if (nextButton) {
                        nextButton.style.display = 'block';
                    } else {
                        nextButton.style.display = 'none';

                    }

                });
        }

        function showNextQuestion(nextIndex) {
            document.getElementById(`question_card_${nextIndex - 1}`).style.display = 'none';
            document.getElementById(`question_card_${nextIndex}`).style.display = 'block';
        }

        function finish(index) {
            document.getElementById('latest_score').textContent += `${final} / ${index}`;
            document.getElementById(`latest_score`).style.display = 'block';
            document.getElementById(`final`).style.display = 'none';
            document.getElementById(`main_page_${index}`).style.display = 'block';

        }
    </script>


</x-app-layout>
