<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 22.08.2024
 * Time: 7:46
 */

namespace App\Services\BotCommands;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssholeStatsCommandHandler extends AbstractCommand
{


    public function execute($message)
    {
        Log::info(get_class($this).' execute');

        $chatId    = $message['message']['chat']['id'];
        $result = DB::select('
SELECT count(pod.user_id) as count,u.username,u.first_name
FROM users u
JOIN (
  select * 
from asshole_of_the_day pod
where pod.chat_id = :chatId
) pod ON (pod.user_id = u.user_id)
group by u.username,u.first_name', ['chatId' => $chatId]
        )
            ;
        $response = $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Ну, распальцовочка по ЛАЛКАМ на сегодняшний день такая :'
        ]);
        foreach ($result as $user)
        {
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $user->first_name.' | '.$user->username.' :       '.$user->count
            ]);
        }
    }

}
