@if(Auth::user()->id == 1)
    <x-app-layout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-end mb-4">
                <a href="{{ route('quiz.edit') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Quiz
                </a>
            </div>
            @if(count($quizzes) == 0)
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                There are no pending quizzes
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($quizzes as $quiz)
                    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                        <img class="w-full h-48 object-cover object-center" src="{{ asset('photos/' . $quiz->photo) }}"
                             alt="{{ $quiz->name }}">
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{ $quiz->name }}</h2>
                            <p class="text-gray-700 mb-2">{{ $quiz->description }}</p>
                            <p class="text-gray-600 text-sm mb-2">Status: {{ $quiz->status }}</p>
                            <p class="text-gray-600 text-sm mb-4">Author: {{ $quiz->user->name }}</p>
                            <div class="flex justify-between">
                                <a href="{{ route('quiz.edit', $quiz->id) }}" class="font-semibold">Edit</a>
                                <form action="{{ route('quiz.delete', $quiz->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-my_color hover:text-red-900 font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </x-app-layout>
@endif
