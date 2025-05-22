<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
     use HasFactory;
     protected $with = ['options'];

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relación con las opciones de la pregunta.
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
    public function getCorrectOptionsAttribute()
    {
        return $this->options->where('is_correct', true);
    }

}
