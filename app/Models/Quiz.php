<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;
    protected $with = ['questions.options'];

    protected $fillable = [
        'title',
        'description',
        'question_count',
        'time_per_question',
        'attempts',
        'status',
        'start_date',
        'end_date',
        'access',
        'show_results',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && 
            now()->between($this->start_date, $this->end_date);
    }

    public function getTotalTimeAttribute(): int
    {
        return $this->question_count * $this->time_per_question;
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    public function attempts()
    {
        return $this->hasMany(\App\Models\QuizAttempt::class);
    }
}
