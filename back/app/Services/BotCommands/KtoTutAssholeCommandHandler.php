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
use App\Models\Users;
use App\Services\PhrasesService;
use DateTime;
use Illuminate\Support\Facades\Log;

class KtoTutAssholeCommandHandler extends AbstractCommand
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

            $phraseForBegin = $this->phrasesService->getSomePhrases('startLookingForAssholeProcess',1);

            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $phraseForBegin[0]['value']
            ]);
            sleep(2);

            $phrases = $this->phrasesService->getSomePhrases('lookingForAssholeProcess',5);
            foreach ($phrases as $phrase){
                $response = $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $phrase['value']
                ]);
                sleep(2);
            }

            $assholeUsername = $asshole['username'];
            $phraseForEnd = $this->phrasesService->getSomePhrases('endLookingForAssholeProcess',1);

            $phraseForEnd = str_replace('$$ASSHOLE$$', "@"."$assholeUsername", $phraseForEnd[0]['value']);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $phraseForEnd
            ]);



        }else{
            $user = Users::query()
                ->where('user_id',$currentAsshole->toArray()['user_id'])
                ->get()
                ->toArray()
                ;
            Log::info(get_class($this).' $asshole here',[$user[0]['username']]);


            $phraseForEnd = $this->phrasesService->getSomePhrases('assholeAlreadyfound',1);
            $assholeUsername = $user[0]['username'];

            $phraseForEnd = str_replace('$$ASSHOLE$$', "$assholeUsername", $phraseForEnd[0]['value']);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $phraseForEnd
            ]);


        }
    }

}