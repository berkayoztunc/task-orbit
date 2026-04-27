<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['lesson_id', 'title', 'description'];

    public function task_submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
