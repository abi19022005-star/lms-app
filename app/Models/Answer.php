<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'jawaban_text',
        'is_correct',
        'score',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'float',
    ];

    // Relasi: Answer milik Attempt
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    // Relasi: Answer milik Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Helper: Cek apakah jawaban essay
    public function isEssay()
    {
        return $this->question->tipe === 'essay';
    }

    // Helper: Cek apakah jawaban multiple choice
    public function isMultipleChoice()
    {
        return $this->question->tipe === 'multiple_choice';
    }

    // Helper: Dapatkan nilai (untuk essay yang sudah dinilai)
    public function getScoreValue()
    {
        return $this->score ?? 0;
    }
}
