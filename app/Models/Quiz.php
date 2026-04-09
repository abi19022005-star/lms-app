<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'judul',
        'passing_score',
    ];

    protected $casts = [
        'passing_score' => 'integer',
    ];

    // Relasi: Quiz milik Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi: Quiz memiliki banyak Question
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relasi: Quiz memiliki banyak Attempt
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Helper: Cek apakah user sudah attempt
    public function hasAttemptByUser($userId)
    {
        return $this->attempts()->where('user_id', $userId)->exists();
    }

    // Helper: Dapatkan attempt user
    public function getAttemptByUser($userId)
    {
        return $this->attempts()->where('user_id', $userId)->first();
    }

    // Helper: Hitung total soal
    public function totalQuestions()
    {
        return $this->questions()->count();
    }

    // Helper: Hitung total skor maksimal
    public function maxScore()
    {
        $mcqCount = $this->questions()->where('tipe', 'multiple_choice')->count();
        $essayCount = $this->questions()->where('tipe', 'essay')->count();
        return $mcqCount + ($essayCount * 100);
    }
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }
}
