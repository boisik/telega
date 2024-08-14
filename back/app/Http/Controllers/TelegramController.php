<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request $request
     * @return Response
     */
    public function getInfo(Request $request)
    {
       Log::info(' ', [$request->toArray()]);
       $message = $request->toArray();
       $userId    = $message['message']['from']['id'];
       $firstName = $message['message']['from']['first_name'];
       $lastNname = $message['message']['from']['last_name'];
       $userName  = $message['message']['from']['username'];
       $chatId    = $message['message']['chat']['id'];
       $type      = $message['message']['chat']['type'];
       $date      = $message['message']['date'];
       $text      = $message['message']['text'];
       Log::info(' ', [$userName]);
       Log::info(' ', [$chatId]);
       Log::info(' ', [date('m/d/Y H:i:s', $date)]);
       Log::info(' ', [$text]);

        if ($type=='private'){
            $telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');
           /* $response = $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'прости, но пока что, могу сказать лишь что ты пидор'
            ]);*/
            $response = $telegram->getMyCommands();
        }
        return $response;

        //https://api.telegram.org/bot6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/getWebhookInfo

    }
}