<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 22.08.2024
 * Time: 7:46
 */

namespace App\Services\BotCommands;

use App\Models\ChatUser;
use App\Models\HandsomeOfTheDay;
use App\Models\Users;
use App\Services\PhrasesService;
use DateTime;
use Illuminate\Support\Facades\Log;

class KtoTutKrasavaCommandHandler extends AbstractCommand
{
    /**
     * @var PhrasesService
     */
    private $phrasesService;

    public function __construct(
        PhrasesService $phrasesService
    )
    {
        parent::__construct();
        $this->phrasesService = $phrasesService;
    }

    public function execute($message)
    {
        Log::info(get_class($this).' execute');
        $bDate= (new DateTime())->format('Y-m-d 00:59:59');
        $eDate= (new DateTime())->format('Y-m-d 23:59:59');
        $chatId    = $message['message']['chat']['id'];
        $currentKrasava =  HandsomeOfTheDay::query()
            ->whereBetween('created_at', [$bDate, $eDate])
            ->where('chat_id',$chatId)
            ->first()
            ;
        if (!$currentKrasava) {
            $users = ChatUser::query()
                ->join('users', 'users.user_id', '=', 'chat_users.user_id')
                ->select('users.user_id', 'users.username')
                ->where('chat_users.chat_id',$chatId)
                ->get()
                ->toArray();


            $krasava = $users[array_rand($users, 1)];
            HandsomeOfTheDay::query()->create(
                [
                    'user_id' => $krasava['user_id'],
                    'chat_id' => $chatId,
                ]
            );
            Log::info(get_class($this).' $krasava was created',$krasava);
            $phraseForBegin = $this->phrasesService->getSomePhrases('startLookingForHandSomeProcess',1);
            Log::info(get_class($this).$phraseForBegin);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $phraseForBegin[0]['value']
            ]);
            sleep(2);

            $phrases = $this->phrasesService->getSomePhrases('lookingForHandSomeProcess',5);
            foreach ($phrases as $phrase){
                $response = $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $phrase['value']
                ]);
                sleep(2);
            }

            sleep(2);


            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'СЕГОДНЯ, АБСОЛЮТНО ЗАСЛУЖЕННАЯ КРАСОТУЛИЧКА ДНЯ - @'.$krasava['username'].' хорошего дня тебе, пусть сегодня будет лучший день на этой неделе,и завтра тоже!!!'
            ]);


        }else{
            $user = Users::query()
                ->where('user_id',$currentKrasava->toArray()['user_id'])
                ->get()
                ->toArray()
                ;
            Log::info(get_class($this).' $krasava here',[$user[0]['username']]);


            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'На сегодня, позиция КРАСАВЫ 🔥🔥🔥 дня уже определена. это '.$user[0]['username'].'🔥🔥🔥 (повторно тегать не будем) но если будешь заебывать,сделаем тебя пидором дня'
            ]);
        }
    }

}