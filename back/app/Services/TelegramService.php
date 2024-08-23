<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 12.08.2024
 * Time: 20:49
 */

namespace App\Services;


use App\Models\ChatUser;
use App\Models\MessageHistory;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use App\Services\BotCommands\KtoTutPidorCommandHandler;

class TelegramService
{
    public $telegram;

    private $commands =
        [
         'ktoTutPidorCommandHandler'  =>'ktotutpidor',
         'PidorStatsCommandHandler'   =>'pidorstats',
         'KtoKrasavaCommandHandler'   =>'ktokrasava',
         'KrasavaStatsCommandHandler' =>'krasavastats',
        ]
    ;
    /**
     * @var KtoTutPidorCommandHandler
     */
    private $ktoTutPidorCommandHandler;

    public function __construct(
        KtoTutPidorCommandHandler $ktoTutPidorCommandHandler = null
    )
    {
        $this->telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');
        $this->ktoTutPidorCommandHandler = $ktoTutPidorCommandHandler;
    }

    /**
     * Анализ входящего сообщения, сохранеие в бд нового юзера, чата, сообщения
     * @param $message
     */
    public function storeUserActivity($message)
    {
        $this->userCheck($message);
        $this->userChatRelationSave($message);
       $this->userMessageSave($message);
    }

    /**
     * Сохранеие в бд нового юзера
     * @param $message
     */
    private function userCheck($message)
    {
        $userId    = $message['message']['from']['id'];
        $firstName = isset($message['message']['from']['first_name']) ? $message['message']['from']['first_name'] : 'undefined';
        $lastName = isset($message['message']['from']['last_name']) ? $message['message']['from']['last_name'] : 'undefined';
        $userName  = $message['message']['from']['username'];

        $user = User::whereUserId($userId);
        if ($user) {
            Log::info(get_class($this).' user exist');
        } else {
            User::query()->create(
                [
                    'user_id' => $userId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $userName
                ]
            );
            Log::info(get_class($this).' user created');
        }
    }

    /**
     * Сохранеие в бд связи юзер - чат
     * @param $message
     */
    private function userChatRelationSave($message)
    {
        $chatId    = $message['message']['chat']['id'];
        $userId    = $message['message']['from']['id'];
        $chatUser = ChatUser::query()
            ->where('user_id', $userId)
            ->where('chat_id',$chatId)
            ->first();
        if (!$chatUser) {
            ChatUser::query()->create(
                [
                    'user_id' => $userId,
                    'chat_id' => $chatId,
                ]
            );
        }
    }

    /**
     * Сохранеие в бд сообщения
     * @param $message
     */
    private function userMessageSave($message)
    {
        MessageHistory::query()->create(
            [
                'user_id' => $message['message']['from']['id'],
                'chat_id' => $message['message']['chat']['id'],
                'type' => 'message',
                'value' => isset($message['message']['text']) ? $message['message']['text'] : 'empty',
                'date' => $message['message']['date']//date('m.d.Y H:i:s', $message['message']['date']),
            ]
        );
    }


    public function detectRequestType($message)
    {
        if(isset($message['message']['entities'])){
            if ($message['message']['entities'][0]['type'] == 'bot_command'){
                $this->executeBotCommand($message);
            }
        }
    }

    public function executeBotCommand($message)
    {
        foreach ($this->commands as  $commandClass => $commandAlias){
            $pos      = strripos($message['message']['text'], $commandAlias);

            if ($pos === false) {
                continue;
            } else {
                $command = $this->$commandClass;
                $command->execute();
            }
        }
    }


}