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
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Log;

class KtoTutKrasavaCommandHandler extends AbstractCommand
{


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

            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'ВНИМАНИЕ, ПОРА ОБЛИЧИТЬ САМУЮ УСПЕШНУЮ КРАСОТУЛИЧКУ СЕГОДНЯШНЕГО ДНЯ'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '.... 📖 🗞 🗒 ☎️ МОНИТОРИМ ЧАТИК'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ОСМАТРИВАЕМ ВАШИ СПИСКИ РЕКОМЕНДУЕМОГО НА WILDBERRYES 🎹 🎁 🔞 👕 👗 👔 👠'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ПРОСМАТРИВАЕМ ПОСЛЕДНИЕ ВИДОСЫ ИЗ LETKA 🎤 🎼 💃 👀'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ОПРАШИВАЕМ ВАШИХ СОСЕДЕЙ  🗣 💥 🍪'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ЗАПРАШИВАЕМ СПРАВКИ О НЕСУДИМОСТИ И СПИСКИ ШТРАФОВ ИЗ ГИБДД 🎥'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....МОЛИМСЯ ГОСПОДУ О ПОСЛАНИИ НАМ ДОПОЛНИТЕЛЬНОЙ ИНФЫ НА EMAIL ✝ 🕌 📧 ☪'
            ]);
            sleep(2);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....ЖДЕМ ЕЩЕ 5 СЕК ДЛЯ ИНТРИГИ ЕБАНОЙ 🕚 🕛'
            ]);
            sleep(5);

            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => '....НУ ИЛИ НЕ 5. ЧТО ТУТ ЕЩЕ СКАЗАТЬ, НУ - ХУЙ! 🐛'
            ]);
            sleep(5);


            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'СЕГОДНЯ, АБСОЛЮТНО ЗАСЛУЖЕННАЯ КРАСОТУЛИЧКА ДНЯ - @'.$krasava['username'].' хорошего дня тебе, пусть сегодня будет лучший день на этой неделе,и завтра тоже!!!'
            ]);


        }else{
            $user = User::query()
                ->where('user_id',$currentKrasava->toArray()['user_id'])
                ->get()
                ->toArray()
                ;
            Log::info(get_class($this).' pidor here',[$user[0]['username']]);


            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'На сегодня, позиция КРАСАВЫ 🔥🔥🔥 дня уже определена. это '.$user[0]['username'].'🔥🔥🔥 (повторно тегать не будем) но если будешь заебывать,сделаем тебя пидором дня'
            ]);
        }
    }

}