<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'question_text', 'correct_answer', 'options', 'photo', 'order'];

    public function quiz(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_question');
    }
}
