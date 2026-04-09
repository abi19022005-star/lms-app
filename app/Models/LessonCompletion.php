<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    // Nonaktifkan timestamps karena kita pakai completed_at manual
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relasi: Completion milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Completion milik Lesson
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
