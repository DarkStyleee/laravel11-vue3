<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * Заполняемые поля.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'parent_id',
    ];

    /**
     * Связь с пользователем.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с постом.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Связь с родительским комментарием.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Связь с дочерними комментариями.
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
