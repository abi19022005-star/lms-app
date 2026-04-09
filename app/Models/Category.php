<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
    ];

    // Relasi: Category memiliki banyak Course
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
