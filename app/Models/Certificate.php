<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'kode_unik',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // Relasi: Certificate milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Certificate milik Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Helper: Format tanggal Indonesia
    public function getFormattedDateAttribute()
    {
        return $this->issued_at->format('d F Y');
    }

    // Helper: Generate kode unik (bisa dipanggil saat membuat)
    public static function generateUniqueCode()
    {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }
}
