<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use App\Services\TelegramService;

class TelegramController extends Controller
{

    private $telegram;
    /**
     * @var TelegramService
     */
    private $telegramService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TelegramService  $telegramService
    )
    {
        $this->telegramService = $telegramService;
    }

    /**
     * webhook telegram
     *
     * @param  Request $request
     * @return Response
     */
    public function getInfo(Request $request)
    {
        return;
/*
 * Структура содержимого в $message
 *[{"update_id":608708731,"message":{"message_id":210,"from":{"id":2050824714,"is_bot":false,"first_name":"Bиктoр","last_name":"Meльников","username":"melnikovusername","language_code":"ru"},"chat":{"id":2050824714,"first_name":"Bиктoр","last_name":"Meльников","username":"melnikovusername","type":"private"},"date":1723779915,"text":"d"}}]
 *
 */

       Log::info(get_class($this), [$request->toArray()]);
       $message = $request->toArray();
       if(!isset($message['message'])){
           return;
       }
       $userId    = $message['message']['from']['id'];
       $firstName = isset($message['message']['from']['first_name']) ? $message['message']['from']['first_name'] : 'undefined';
       $lastName = isset($message['message']['from']['last_name']) ? $message['message']['from']['last_name'] : 'undefined';
       $userName  = $message['message']['from']['username'];

       $this->telegramService->storeUserActivity($message);
       $this->telegramService->detectRequestType($message);

        $chatType  = $message['message']['chat']['type'];
        $chatId    = $message['message']['chat']['id'];
/*        $chatId    = $message['message']['chat']['id'];
        $chatType  = $message['message']['chat']['type'];
        $date      = $message['message']['date'];
        $text      = isset($message['message']['text']) ? $message['message']['text'] : 'empty';
        Log::info(' ', [$userName]);
        Log::info(' ', [$chatId]);
        Log::info(' ', [date('m.d.Y H:i:s', $date)]);
        Log::info(' ', [$text]);*/



        if ($chatType=='private'){
            $response = $this->telegramService->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'прости, но пока что, могу сказать лишь что ты пидор'
            ]);
        }

    /*    $response = $this->telegramService->telegram->sendMessage([
            'chat_id' => '-1002245688647',
            'text' => '@lestet_94 несомненно пока что пидор всех дней пока функционал не будет реализован 💋💋💋💋💋'
        ]);*/

        //https://api.telegram.org/bot6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/setWebhook?url=https://f0c6-194-15-147-11.ngrok-free.app/webhook


    }


    public function test(Request $request)
    {
        return;
        $bDate= (new DateTime())->format('Y-m-d 00:59:59');
        $eDate= (new DateTime())->format('Y-m-d 23:59:59');

       $mh =  MessageHistory::query()->whereBetween('created_at', [$bDate, $eDate])->first();
dd($mh);
        /*    $response = $this->telegramService->telegram->sendMessage([
                'chat_id' => '-1002245688647',
                'text' => '@lestet_94 несомненно пока что пидор всех дней пока функционал не будет реализован 💋💋💋💋💋'
            ]);*/

        //https://api.telegram.org/bot6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/setWebhook?url=https://ef71-185-230-143-47.ngrok-free.app/webhook


    }
}