<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Muestra el listado de quizzes disponibles
     */
    public function index()
    {
        $user = Auth::user();
        
        $quizzes = Quiz::query()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->withCount('questions')
            ->orderBy('start_date')
            ->get()
            ->filter(function ($quiz) use ($user) {
                return $user->quizAttempts()
                    ->where('quiz_id', $quiz->id)
                    ->count() < $quiz->attempts;
            });
            

        return view('quizzes.index', [
            'quizzes' => $quizzes,
            'attemptedQuizzes' => $user->quizAttempts()->pluck('quiz_id')->unique()
        ]);
    }

    /**
     * Muestra un quiz específico
     */
    public function show(Quiz $quiz)
    {
        $user = Auth::user();
        
        // Validación de acceso
        if (!$this->validateQuizAccess($user, $quiz)) {
            return redirect()->route('quizzes.index');
        }

        return view('quizzes.show', [
            'quiz' => $quiz->load(['questions.options']),
            'remainingAttempts' => $quiz->attempts - $user->quizAttempts()->where('quiz_id', $quiz->id)->count()
        ]);
    }

    /**
     * Validación de acceso a quiz
     */
    protected function validateQuizAccess($user, $quiz)
    {
        if ($quiz->status !== 'active') {
            $this->setError('Este quiz no está activo actualmente');
            return false;
        }

        if (now()->lt($quiz->start_date)) {
            $this->setError('Este quiz comenzará el '.$quiz->start_date->format('d/m/Y H:i'));
            return false;
        }

        if (now()->gt($quiz->end_date)) {
            $this->setError('Este quiz finalizó el '.$quiz->end_date->format('d/m/Y H:i'));
            return false;
        }

        $attempts = $user->quizAttempts()->where('quiz_id', $quiz->id)->count();
        if ($attempts >= $quiz->attempts) {
            $this->setError('Ya has completado el máximo de intentos para este quiz');
            return false;
        }

        return true;
    }

    /**
     * Maneja los mensajes de error
     */
    protected function setError($message)
    {
        session()->flash('error', $message);
    }

    /**
     * Endpoint API para validar acceso via AJAX
     */
    public function checkAccess(Quiz $quiz)
    {
        $user = Auth::user();
        $canAccess = $this->validateQuizAccess($user, $quiz);
        
        return response()->json([
            'success' => $canAccess,
            'message' => $canAccess ? 'Access granted' : session('error'),
            'remainingAttempts' => $canAccess ? 
                $quiz->attempts - $user->quizAttempts()->where('quiz_id', $quiz->id)->count() : 0
        ]);
    }

    public function submit(Quiz $quiz, Request $request)
    {
        $request->merge([
            'answers' => $request->input('answers', []),
            'time_spent' => $request->input('time_spent')
        ]);

        $user = auth()->user();

        // Verificar que el usuario puede enviar respuestas
        if ($user->quizAttempts()->where('quiz_id', $quiz->id)->count() >= $quiz->attempts) {
            return response()->json([
                'success' => false,
                'message' => 'Has excedido el número máximo de intentos'
            ], 403);
        }

        // Cargar preguntas y opciones
        $quiz->load('questions.options');

        $results = [
            'score' => 0,
            'total_questions' => $quiz->questions->count(),
            'correct_answers' => 0,
            'incorrect_answers' => 0,
            'details' => []
        ];

        foreach ($quiz->questions as $question) {
            $userAnswer = $request->input("answers.{$question->id}");
            $isCorrect = false;
            $correctAnswer = null;

            if ($question->type === 'multiple_choice') {
                $correctOption = $question->options->where('is_correct', true)->first();
                $correctAnswer = $correctOption ? $correctOption->option : null;

                $selectedOption = $question->options->find($userAnswer);
                $isCorrect = $selectedOption && $selectedOption->is_correct;
            } else {
                $correctAnswer = 'Respuesta evaluada por el profesor';
            }

            if ($isCorrect) {
                $results['score']++;
                $results['correct_answers']++;
            } elseif (!is_null($userAnswer)) {
                $results['incorrect_answers']++;
            }

            $results['details'][] = [
                'question' => $question->question,
                'user_answer' => $question->type === 'multiple_choice'
                    ? ($selectedOption->option ?? 'No respondida')
                    : $userAnswer,
                'is_correct' => $isCorrect,
                'correct_answer' => $correctAnswer
            ];
        }

        // Guardar el intento
        $user->quizAttempts()->create([
            'quiz_id' => $quiz->id,
            'score' => $results['score'],
            'total_questions' => $results['total_questions'],
            'answers_data' => $results,
            'time_spent' => $request->time_spent,
            'completed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                ...$results,
                'show_details' => $quiz->show_results
            ]
        ]);
    }


    public function results()
    {
        $user = auth()->user();

        $attempts = $user->quizAttempts()
            ->with(['quiz', 'quiz.questions'])
            ->latest('completed_at')
            ->get();

        return view('quizzes.results', compact('attempts'));
    }
}