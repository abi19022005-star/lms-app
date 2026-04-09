<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Lesson extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $fillable = [
        'course_id',
        'judul',
        'konten_teks',
        'tipe',
        'url_video',
        'file_pdf',
        'order',
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    // Relasi: Lesson milik Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi: Lesson memiliki banyak completion
    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Helper: Cek apakah lesson sudah diselesaikan oleh user
    public function isCompletedByUser($userId)
    {
        return $this->completions()->where('user_id', $userId)->exists();
    }

    // Helper: Dapatkan tipe konten
    public function getContentTypeAttribute()
    {
        $types = [
            'video' => 'Video',
            'teks' => 'Teks',
            'pdf' => 'PDF',
        ];
        return $types[$this->tipe] ?? 'Unknown';
    }
}
