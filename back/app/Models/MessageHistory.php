<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MessageHistory extends Model
{
    use HasFactory;
    protected $table = 'message_history';

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
        'chat_id'
    ];

    protected $casts = [

        'date' => 'datetime',
    ];

}
