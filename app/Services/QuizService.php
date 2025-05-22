<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Models\QuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * Verifica si un usuario puede realizar un quiz
     */
    /*public function canUserTakeQuiz(User $user, Quiz $quiz): bool
    {
        // Verificar si el quiz está activo
        if (!$this->isQuizAvailable($quiz)) {
            return false;
        }

        // Verificar intentos permitidos
        $attempts = $user->quizAttempts()
        ->where('quiz_id', $quiz->id)
        ->count();

        return $attempts < $quiz->attempts;
    }

    /**
     * Verifica si un quiz está disponible
     */
    public function canUserTakeQuiz(User $user, Quiz $quiz): bool
{
    if ($quiz->status !== 'active') {
        \Log::debug("Quiz {$quiz->id} no está activo");
        return false;
    }

    if (now()->lt($quiz->start_date)) {
        \Log::debug("Quiz {$quiz->id} no ha comenzado (start_date: {$quiz->start_date})");
        return false;
    }

    if (now()->gt($quiz->end_date)) {
        \Log::debug("Quiz {$quiz->id} ya ha finalizado (end_date: {$quiz->end_date})");
        return false;
    }

    $attemptsCount = $user->quizAttempts()
        ->where('quiz_id', $quiz->id)
        ->count();

    if ($attemptsCount >= $quiz->attempts) {
        \Log::debug("Usuario {$user->id} ha excedido intentos para quiz {$quiz->id} ({$attemptsCount}/{$quiz->attempts})");
        return false;
    }

    return true;
}
    public function isQuizAvailable(Quiz $quiz): bool
    {
        return $quiz->status === 'active' && 
               Carbon::now()->between($quiz->start_date, $quiz->end_date);
    }

    /**
     * Obtiene el número de intentos de un usuario para un quiz
     */
    public function getUserQuizAttemptsCount(User $user, Quiz $quiz): int
    {
        return $user->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->count();
    }

    /**
     * Calcula el puntaje y resultados de un quiz
     */
    public function calculateQuizResults(Quiz $quiz, array $userAnswers): array
    {
        $results = [
            'score' => 0,
            'total_questions' => $quiz->questions->count(),
            'correct_answers' => 0,
            'incorrect_answers' => 0,
            'unanswered' => 0,
            'details' => []
        ];

        foreach ($quiz->questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;
            $isCorrect = false;

            if ($question->type === 'multiple_choice') {
                $isCorrect = $this->checkMultipleChoiceAnswer($question, $userAnswer);
            } elseif ($question->type === 'open') {
                // Lógica para preguntas abiertas puede implementarse aquí
                continue;
            }

            if ($isCorrect) {
                $results['score'] += $question->points ?? 1;
                $results['correct_answers']++;
            } elseif ($userAnswer !== null) {
                $results['incorrect_answers']++;
            } else {
                $results['unanswered']++;
            }

            $results['details'][$question->id] = [
                'question' => $question->question,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'correct_options' => $question->options->where('is_correct', true)->pluck('id')->toArray(),
                'question_type' => $question->type
            ];
        }

        return $results;
    }

    /**
     * Verifica una respuesta de opción múltiple
     */
    protected function checkMultipleChoiceAnswer($question, $userAnswer): bool
    {
        if ($userAnswer === null) {
            return false;
        }

        $correctOptions = $question->options
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        // Para respuestas de selección única
        if (is_numeric($userAnswer)) {
            return in_array($userAnswer, $correctOptions);
        }

        // Para respuestas múltiples (si las implementas en el futuro)
        if (is_array($userAnswer)) {
            return empty(array_diff($correctOptions, $userAnswer)) && 
                   empty(array_diff($userAnswer, $correctOptions));
        }

        return false;
    }

    /**
     * Registra un intento de quiz en la base de datos
     */
    public function recordQuizAttempt(User $user, Quiz $quiz, array $results): QuizAttempt
    {
        return DB::transaction(function () use ($user, $quiz, $results) {
            return $user->quizAttempts()->create([
                'quiz_id' => $quiz->id,
                'score' => $results['score'],
                'total_questions' => $results['total_questions'],
                'correct_answers' => $results['correct_answers'],
                'incorrect_answers' => $results['incorrect_answers'],
                'unanswered' => $results['unanswered'],
                'answers_data' => $results['details'],
                'completed_at' => now()
            ]);
        });
    }

    /**
     * Obtiene los mejores intentos de un usuario para un quiz
     */
    public function getUserBestAttempts(User $user, Quiz $quiz, int $limit = 3): array
    {
        return $user->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('score')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}