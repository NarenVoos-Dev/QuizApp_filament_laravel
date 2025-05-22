<div>
    <div class="mb-4">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
        </div>
        <div class="flex justify-between mt-1 text-sm text-gray-500">
            <span>Progreso: {{ $progress }}%</span>
            <span>Pregunta {{ $currentQuestionIndex + 1 }} de {{ $quiz->questions->count() }}</span>
        </div>
    </div>
    
    <div class="p-6 mb-6 rounded-lg bg-gray-50">
        <h3 class="mb-4 text-lg font-semibold">{{ $question->question }}</h3>
        
        @if($question->type === 'multiple_choice')
            <div class="space-y-3">
                @foreach($question->options as $option)
                    <label class="flex items-center p-3 space-x-3 border rounded-lg cursor-pointer hover:bg-gray-100">
                        <input 
                            type="radio" 
                            name="answers[{{ $question->id }}]" 
                            wire:model="answers.{{ $question->id }}" 
                            value="{{ $option->id }}" 
                            class="w-5 h-5 text-blue-600"
                        >
                        <span>{{ $option->option }}</span>
                    </label>
                @endforeach
            </div>
        @else
            <textarea 
                wire:model="answers.{{ $question->id }}" 
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                rows="4"
                placeholder="Escribe tu respuesta aquí..."
            ></textarea>
        @endif
    </div>
    
    <div class="flex justify-between">
        @if($currentQuestionIndex > 0)
            <button 
                wire:click="previousQuestion" 
                class="px-4 py-2 text-gray-700 transition bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                Anterior
            </button>
        @else
            <div></div> <!-- Espacio vacío para mantener el flex -->
        @endif
        
        @if($currentQuestionIndex < $quiz->questions->count() - 1)
            <button 
                wire:click="nextQuestion" 
                class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700"
                wire:loading.attr="disabled"
            >
                Siguiente
            </button>
        @else
            <button 
                wire:click="submitQuiz" 
                class="px-4 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700"
                wire:loading.attr="disabled"
            >
                Finalizar Quiz
            </button>
        @endif
    </div>
</div>

@if($quizCompleted)
    <div class="p-6 mt-6 bg-white rounded-lg shadow">
        <h3 class="mb-4 text-2xl font-bold">Resultados del Quiz</h3>
        
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-4 text-center rounded-lg bg-green-50">
                <div class="font-medium text-green-800">Correctas</div>
                <div class="text-3xl font-bold text-green-600">
                    {{ $results['correct_answers'] }}
                </div>
            </div>
            <div class="p-4 text-center rounded-lg bg-red-50">
                <div class="font-medium text-red-800">Incorrectas</div>
                <div class="text-3xl font-bold text-red-600">
                    {{ $results['incorrect_answers'] }}
                </div>
            </div>
            <div class="p-4 text-center rounded-lg bg-blue-50">
                <div class="font-medium text-blue-800">Puntaje</div>
                <div class="text-3xl font-bold text-blue-600">
                    {{ $results['score'] }}/{{ $results['total_questions'] }}
                </div>
            </div>
        </div>
        
        @if($quiz->show_results)
            <div class="mt-6">
                <h4 class="mb-4 text-lg font-semibold">Detalle de respuestas:</h4>
                
                @foreach($results['details'] as $questionId => $detail)
                    @php 
                        $question = $quiz->questions->firstWhere('id', $questionId);
                    @endphp
                    
                    <div class="mb-6 p-4 border rounded-lg {{ $detail['is_correct'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <h5 class="mb-2 font-medium">
                            Pregunta {{ $loop->iteration }}: {{ $detail['question'] }}
                        </h5>
                        
                        @if($detail['question_type'] === 'multiple_choice')
                            <p class="mb-1">
                                <span class="font-medium">Tu respuesta:</span> 
                                {{ $question->options->firstWhere('id', $detail['user_answer'])?->option ?? 'No respondida' }}
                            </p>
                            
                            @if(!$detail['is_correct'])
                                <p class="font-medium">Respuesta(s) correcta(s):</p>
                                <ul class="pl-5 list-disc">
                                    @foreach($detail['correct_options'] as $optionId)
                                        <li>{{ $question->options->firstWhere('id', $optionId)->option }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-6">
            <a href="{{ route('quizzes.index') }}" 
               class="inline-block px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                Volver a la lista de quizzes
            </a>
        </div>
    </div>
@endif