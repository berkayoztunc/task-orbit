<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['intern_register_id', 'lesson_id', 'status', 'date'];

    public function intern_register()
    {
        return $this->belongsTo(InternRegister::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
