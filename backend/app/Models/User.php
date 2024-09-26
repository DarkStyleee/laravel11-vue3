<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\AccessGroup;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Заполняемые атрибуты.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Скрытые атрибуты.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Атрибуты для кастинга.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create();
        });
    }

    /**
     * Связь с постами.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Связь с комментариями пользователя.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Связь с ролями пользователя.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Связь с группами доступов пользователя.
     */
    public function accessGroups()
    {
        return $this->belongsToMany(AccessGroup::class, 'user_access_groups')->withTimestamps();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
