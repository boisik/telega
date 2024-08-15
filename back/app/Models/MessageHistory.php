<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MessageHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'type',
        'value',
        'date',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'date' => 'datetime',
    ];

}
