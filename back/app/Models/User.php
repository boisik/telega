<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];


    public static function whereUserId($userId)
    {
        return static::query()->where('user_id', $userId);
    }
}
