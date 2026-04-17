<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['image_id', 'media_id', 'media_type'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
