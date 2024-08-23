<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 23.08.2024
 * Time: 7:20
 */

namespace App\Services\BotCommands;

use Telegram\Bot\Api;

abstract class AbstractCommand
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');
    }
}