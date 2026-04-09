<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'nip',
        'nis',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============ RELATIONSHIPS ============

    // Relasi: User sebagai Guru (membuat kursus)
    public function coursesTaught(): HasMany
    {
        return $this->hasMany(Course::class, 'guru_id');
    }

    // Relasi: User sebagai Siswa (enroll kursus)
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    public function hasEnrolled($courseId)
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->exists();
    }

    // Relasi: User menyelesaikan lesson
    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Relasi: User mengerjakan kuis
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Relasi: User mendapat sertifikat
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // ============ ROLE CHECK METHODS ============

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // ============ HELPER METHODS ============

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
