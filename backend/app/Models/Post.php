<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Заполняемые поля.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * Связь с пользователем.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с комментариями.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
