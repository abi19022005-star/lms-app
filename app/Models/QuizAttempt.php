<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\QuizPassed;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'submitted_at',
        'total_score',
        'is_graded',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'total_score' => 'float',
        'is_graded' => 'boolean',
    ];

    // Relasi: Attempt milik Quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Relasi: Attempt milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Attempt memiliki banyak Answer
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Method: Hitung ulang total score
    public function recalculateTotalScore()
    {
        $answers = $this->answers()->with('question')->get();
        $totalScore = 0;
        $maxScore = 0;

        foreach ($answers as $answer) {
            if ($answer->question->tipe == 'multiple_choice') {
                $maxScore += 1;
                if ($answer->is_correct) {
                    $totalScore += 1;
                }
            } else { // essay
                $maxScore += 100; // Bobot essay 100 poin per soal
                if (!is_null($answer->score)) {
                    $totalScore += $answer->score;
                }
            }
        }

        $this->total_score = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

        // Cek apakah semua essay sudah dinilai
        $this->is_graded = $this->answers()
            ->whereHas('question', fn($q) => $q->where('tipe', 'essay'))
            ->whereNull('score')
            ->doesntExist();

        $this->save();

        // Jika lulus, trigger event
        if ($this->total_score >= $this->quiz->passing_score) {
            event(new QuizPassed($this));
        }

        return $this->total_score;
    }

    // Helper: Cek apakah sudah disubmit
    public function isSubmitted()
    {
        return !is_null($this->submitted_at);
    }

    // Helper: Cek apakah lulus
    public function isPassed()
    {
        return $this->total_score >= $this->quiz->passing_score;
    }
}
