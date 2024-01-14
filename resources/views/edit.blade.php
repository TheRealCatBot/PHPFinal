<x-app-layout>

    <form action="{{ isset($quiz) ? route('quiz.edit', $quiz->id) : route('quiz.edit') }}" method="POST"
          enctype="multipart/form-data" class="p-4 border rounded">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $quiz->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" name="description"
                      required>{{ $quiz->description ?? '' }}</textarea>
        </div>

        @if($quiz->photo)
            <div class="mb-3">
                <img src="{{ asset('photos/' . $quiz->photo) }}" alt="" class="img-fluid mb-3">
            </div>
        @endif

        <div class="mb-3">
            <label for="photo" class="form-label">Quiz Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo">
        </div>

        @if(Auth::user()->id == 1)
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status">
                    <option value="pending" {{ ($quiz->status ?? '') == 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="approved" {{ ($quiz->status ?? '') == 'approved' ? 'selected' : '' }}>approved
                    </option>
                    <option value="rejected" {{ ($quiz->status ?? '') == 'rejected' ? 'selected' : '' }}>rejected
                    </option>
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Save</button>
        @if($quiz->id)
        <h1>Questions: </h1>

        <div id="questions-container">
            <!-- Dynamic questions will be added here -->
            @foreach ($quiz->questions()->orderBy('order')->get() as $index => $question)
                <div class="question-set p-4 border rounded">
                    <div class="mb-3">
                        <label>Order:</label>
                        <input type="number" name="questions[{{ $index }}][order]" class="form-control"
                               value="{{ $question->order }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Question:</label>
                        <input type="text" name="questions[{{ $index }}][question_text]" class="form-control"
                               value="{{ $question->question_text }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Options:</label>
                        <textarea name="questions[{{ $index }}][options]" class="form-control"
                                  required>{{ implode(',', json_decode($question->options)) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Correct Answer:</label>
                        <input type="text" name="questions[{{ $index }}][correct_answer]" class="form-control"
                               value="{{ $question->correct_answer }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Photo:</label>
                        <input type="file" name="questions[{{ $index }}][question_photo]" class="form-control"
                               value="{{ asset($question->photo) }}" >
                        <img src="{{ asset($question->photo) }}">
                    </div>
                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                </div>
            @endforeach
        </div>
    </form>

    <button type="button" id="add-question" class="btn btn-warning m-4">Add Question</button>
    @endif
    <footer style="padding-bottom: 100px">

    </footer>
</x-app-layout>


<script>
    document.getElementById('add-question').addEventListener('click', function () {
        const container = document.getElementById('questions-container');
        const questionNumber = container.children.length + 1;

        container.innerHTML += `
        <div class="question-set p-4 border rounded">
            <div class="mb-3">
                <label>Order:</label>
                <input type="number" name="questions[${questionNumber}][order]" class="form-control" placeholder="Enter the position of the question" required>
            </div>

            <div class="mb-3">
                <label>Question ${questionNumber}:</label>
                <input type="text" name="questions[${questionNumber}][question_text]" class="form-control" placeholder="Enter the question" required>
            </div>

            <div class="mb-3">
                <label>Options:</label>
                <textarea name="questions[${questionNumber}][options]" class="form-control" placeholder="Enter options separated by a comma" required></textarea>
            </div>

            <div class="mb-3">
                <label>Correct Answer:</label>
                <input type="text" name="questions[${questionNumber}][correct_answer]" class="form-control" placeholder="Enter the correct answer" required>
            </div>

            <div class="mb-3">
                <label>Question Photo:</label>
                <input type="file" name="questions[${questionNumber}][question_photo]" class="form-control" required>
            </div>
        </div>
    `;
    });

</script>
