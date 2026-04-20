<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    protected $fillable = ['message', 'user_id'];

    // Yorumu yapan kullanıcıya ait ilişki
    // Bir yorum sadece bir kullanıcıya ait olabilir
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bu yorumun bağlı olduğu commantable kayıtları
    // Bir yorumun birden fazla commantable kaydı olabilir
    public function commantables()
    {
        return $this->hasMany(Commantable::class);
    }
}