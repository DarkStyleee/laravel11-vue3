<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AccessGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Связь с пользователями.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_access_groups')->withTimestamps();
    }
}