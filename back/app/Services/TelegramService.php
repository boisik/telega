<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 12.08.2024
 * Time: 20:49
 */

namespace App\Services;


use App\Models\User;
use Telegram\Bot\Api;

class TelegramService
{
    public $telegram;

    public function __construct()
    {
        $this->telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');
    }

    public function initTelegramUser($message)
    {
        $userId    = $message['message']['from']['id'];
        $firstName = $message['message']['from']['first_name'];
        $lastName = $message['message']['from']['last_name'];
        $userName  = $message['message']['from']['username'];

        $user = User::whereUserId($userId);

        if ($user) {
            return $user;
        } else {
            $user = User::query()->create(
                [
                    'user_id' => $userId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $userName
                ]
            );
            return $user;
        }
    }
}