<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Services\QuizService;

class QuizTaker extends Component
{
    public Quiz $quiz;
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $quizCompleted = false;
    public $results = [];
    
    protected $listeners = ['timerUpdated' => 'updateTimer'];
    
    protected QuizService $quizService;
    
    public function boot(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }
    
    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load('questions.options');
    }
    
    public function nextQuestion()
    {
        $this->validateCurrentQuestion();
        $this->currentQuestionIndex++;
    }
    
    public function previousQuestion()
    {
        $this->currentQuestionIndex--;
    }
    
    public function submitQuiz()
    {
        $this->validateCurrentQuestion();
        
        // Calcular resultados usando el servicio
        $this->results = $this->quizService->calculateQuizResults(
            $this->quiz, 
            $this->answers
        );
        
        // Registrar el intento
        $this->quizService->recordQuizAttempt(
            auth()->user(),
            $this->quiz,
            $this->results
        );
        
        $this->quizCompleted = true;
    }
    
    protected function validateCurrentQuestion()
    {
        $question = $this->quiz->questions[$this->currentQuestionIndex];
        
        if ($question->type === 'multiple_choice' && !isset($this->answers[$question->id])) {
            $this->addError('answers.'.$question->id, 'Debes seleccionar una respuesta');
            throw new \Exception('Debes seleccionar una respuesta');
        }
    }
    
    public function render()
    {
        return view('livewire.quiz-taker', [
            'question' => $this->quiz->questions[$this->currentQuestionIndex] ?? null,
            'progress' => $this->quiz->questions->count() > 0 
                ? round(($this->currentIndex + 1) / $this->quiz->questions->count() * 100) 
                : 0
        ]);
    }
}