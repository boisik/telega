<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HandsomeOfTheDay extends Model
{
    use HasFactory;
    protected $table = 'handsome_of_the_day';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'chat_id',
        'user_id'
    ];


}
