<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 22.08.2024
 * Time: 7:46
 */

namespace App\Services\BotCommands;

use App\Models\ChatUser;
use App\Models\AssholeOfTheDay;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Log;

class KtoTutAssholeCommandHandler extends AbstractCommand
{


    public function execute($message)
    {
        Log::info(get_class($this).' execute');
        $bDate= (new DateTime())->format('Y-m-d 00:59:59');
        $eDate= (new DateTime())->format('Y-m-d 23:59:59');
        $chatId    = $message['message']['chat']['id'];
        $currentAsshole =  AssholeOfTheDay::query()
            ->whereBetween('created_at', [$bDate, $eDate])
            ->where('chat_id',$chatId)
            ->first()
            ;
        if (!$currentAsshole) {
            $users = ChatUser::query()
                ->join('users', 'users.user_id', '=', 'chat_users.user_id')
                ->select('users.user_id', 'users.username')
                ->where('chat_users.chat_id',$chatId)
                ->get()
                ->toArray();


            $asshole = $users[array_rand($users, 1)];
            AssholeOfTheDay::query()->create(
                [
                    'user_id' => $asshole['user_id'],
                    'chat_id' => $chatId,
                ]
            );
            Log::info(get_class($this).' $asshole was created',$asshole);

            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'ВНИМАНИЕ, ВРЕМЯ ПОИСКА САМОЙ ПИДОРСКОЙ ОБОЯШКИ НА СЕГОДНЯ'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ПЕРЕЧИТЫВАЕМ ЧАТ ЗА СУТКИ 📰 🗞 🗒 📑'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ИЗУЧАЕМ ГОРОСКОПЫ 🔭 🌕 🌖 🌗 🌘 🌑'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ЗАГЛЯДЫВАЕМ В ДИЮ 🍕 🍷 🍺 🍸'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....МОНИТОРИМ РЕЙТИНГИ В КОНТЕРСТРАЙКАХ 🔫🔫🔫🔫 🔪🔪🔪🔪🔪🔪'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ИЩЕМ ПРОСРОЧКУ В ЛЕТУАЛЕ 💄 💅 '
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....СМОТРИМ РАСПИСАНИЕ ГАРМОНИИ 👶 👦 👧 👨'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ОЗНАКАМЛИВАЕМСЯ С ГОСТЕВЫМ СПИСКОМ НА ДЕЙКАЛО 4     (и 32)  😺 😻 😾 🐈🐈🐈🐈'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ФИЛЬТРУЕМ СВОДКИ МВД 🚓🚓🚓'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'На сегодня,🎯🎯🎯🎯🎯🎯🎯 почетный пидор дня - @'.$asshole['username']
            ]);


        }else{
            $user = User::query()
                ->where('user_id',$currentAsshole->toArray()['user_id'])
                ->get()
                ->toArray()
                ;
            Log::info(get_class($this).' $asshole here',[$user[0]['username']]);


            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'На сегодня, позиция Пидора дня уже определена. это '.$user[0]['username'].'💋💋💋 (повторно тегать не будем) но если будешь заебывать, пересчитаем в твою пользу 🌺'
            ]);
        }
    }

}