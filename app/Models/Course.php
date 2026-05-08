<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'kategori_id',
        'judul',
        'slug',
        'deskripsi',
        'thumbnail',
        'harga',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // Boot method untuk generate slug otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('judul')) {
                $model->slug = Str::slug($model->judul);
            }
        });
    }

    // Relasi: Course milik Guru
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi: Course milik Category
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }
    public function kategori()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Course memiliki banyak Lesson
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order','asc');
    }

    // Relasi: Course memiliki banyak Enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi: Course memiliki banyak Quiz
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // Relasi: Course memiliki banyak Certificate
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Helper: Cek apakah user sudah enroll
    public function isEnrolledBy($userId)
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    // Helper: Dapatkan progress user
    public function getProgressForUser($userId)
    {
        $enrollment = $this->enrollments()->where('user_id', $userId)->first();
        return $enrollment ? $enrollment->progress : 0;
    }

    // Helper: Cek apakah course gratis
    public function isFree()
    {
        return $this->harga == 0;
    }

    // Override route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
