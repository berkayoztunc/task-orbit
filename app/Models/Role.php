<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    use HasFactory;

    public function profile()
    {
        return $this->hasMany(Profile::class);
    }
}
