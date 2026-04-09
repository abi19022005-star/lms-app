<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Events\CourseCompleted;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress' => 'integer',
    ];

    // Relasi: Enrollment milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Enrollment milik Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi: Lesson completions untuk user di course ini
    public function lessonCompletions(): HasManyThrough
    {
        return $this->hasManyThrough(
            LessonCompletion::class,
            Lesson::class,
            'course_id',
            'lesson_id',
            'course_id',
            'id'
        )->where('lesson_completions.user_id', $this->user_id);
    }

    // Method: Refresh progress dari lesson completions
    public function refreshProgress()
    {
        $totalLessons = $this->course->lessons()->count();

        if ($totalLessons == 0) {
            $this->progress = 0;
        } else {
            $completedLessons = LessonCompletion::where('user_id', $this->user_id)
                ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
                ->count();
            $this->progress = round(($completedLessons / $totalLessons) * 100);
        }

        // Jika progress 100% dan status belum completed
        if ($this->progress == 100 && $this->status !== 'completed') {
            $this->status = 'completed';
            $this->completed_at = now();
            event(new CourseCompleted($this));
        }

        $this->save();
        return $this->progress;
    }

    // Helper: Cek apakah sudah completed
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

}
