<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commantable extends Model
{
    // Bu tabloda created_at ve updated_at sütunları yok
    // Laravel varsayılan olarak timestamp arar, false diyerek kapatıyoruz
    public $timestamps = false;
    use HasFactory;

    // command_id → hangi yoruma ait
    // commantable_id → hangi kaydın id'si (ders, görev, staj)
    // commantable_type → hangi model (Lesson, Task, Internship)
    protected $fillable = ['command_id', 'commantable_id', 'commantable_type'];

    // Bu kaydın ait olduğu yorum
    // Bir commantable sadece bir yoruma ait olabilir
    public function command()
    {
        return $this->belongsTo(Command::class);
    }

    // Polimorfik ilişki
    // commantable_type e bakarak hangi modele ait olduğunu anlar
    // Örneğin Lesson, Task veya Internship olabilir
    public function commantable()
    {
        return $this->morphTo();
    }
}