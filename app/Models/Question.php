<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'tipe',
        'pertanyaan',
        'opsi',
        'jawaban_benar',
    ];

    protected $casts = [
        'opsi' => 'array', // Auto cast JSON ke array
    ];

    // Relasi: Question milik Quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Relasi: Question memiliki banyak Answer
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Helper: Cek apakah multiple choice
    public function isMultipleChoice()
    {
        return $this->tipe === 'multiple_choice';
    }

    // Helper: Cek apakah essay
    public function isEssay()
    {
        return $this->tipe === 'essay';
    }

    // Helper: Dapatkan opsi sebagai array
    public function getOptionsArray()
    {
        return is_array($this->opsi) ? $this->opsi : json_decode($this->opsi, true);
    }
}
