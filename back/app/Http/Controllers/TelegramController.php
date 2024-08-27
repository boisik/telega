<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use App\Services\PhrasesService;
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
     * @var PhrasesService
     */
    private $phrasesService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TelegramService  $telegramService,
        PhrasesService $phrasesService
    )
    {
        $this->telegramService = $telegramService;
        $this->phrasesService = $phrasesService;
    }

    /**
     * webhook telegram
     *
     * @param  Request $request
     * @return Response
     */
    public function getInfo(Request $request)
    {
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
       $userName  = isset($message['message']['from']['username']) ? $message['message']['from']['username'] : $firstName.'###'.$lastName;

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
          /* $ch = curl_init();

           curl_setopt($ch, CURLOPT_URL, 'http://api.weatherstack.com/current?access_key=6f364f29c9394a760229e0f7e29bbeef&query=Kerch,Ukraine');
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

           $result = curl_exec($ch);
           if (curl_errno($ch)) {
               echo 'Error:' . curl_error($ch);
           }
           curl_close($ch);
           $res = json_decode($result);

           $locationRow = "Location :  ".$res->location->name." ".$res->location->region."  ⚓ 🏚🏚 🌈 💞" ;
           $timeZoneRow = "Timezone :  ".$res->location->timezone_id."  ".$res->location->localtime."  ⏲⏰🕰";
           $temperatureRow = "Temperature : ".$res->current->temperature."  🌡🌡🌡🌡  Feelslike :  ".$res->current->feelslike;
           $weatherDescriptionsRow = "Text description : ".$res->current->weather_descriptions[0]." 🆗🆗🆗";
           $humidityRow = "Humidity : ".$res->current->humidity."  💧";
           $windRow = "Wind: ".$res->current->wind_speed."m/s   Direction(не все поймут):".$res->current->wind_dir." ♻️♻️♻️♻";

           $response = $this->telegramService->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $locationRow."\n"."\n".$timeZoneRow."\n"."\n".$temperatureRow."\n"."\n".$weatherDescriptionsRow."\n"."\n".$humidityRow."\n"."\n".$windRow
                ]);*/

        }

    /*    $response = $this->telegramService->telegram->sendMessage([
            'chat_id' => '-1002245688647',
            'text' => '@lestet_94 несомненно пока что пидор всех дней пока функционал не будет реализован 💋💋💋💋💋'
        ]);*/

        //https://api.telegram.org/bot6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/setWebhook?url=https://bd97-194-15-147-11.ngrok-free.app/webhook


    }


    public function test(Request $request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://api.weatherstack.com/current?access_key=6f364f29c9394a760229e0f7e29bbeef&query=Kerch,Ukraine');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
$res = json_decode($result);

$locationRow = 'Location :'.$res->location->name.' '.$res->location->region.'  ⚓ 🏚🏚 🌈 💞';
$timeZoneRow = 'Timezone :'.$res->location->timezone_id.'   '.$res->location->localtime.'  ⏲⏰🕰';
$temperatureRow = 'Temperature :'.$res->current->temperature.'  🌡🌡🌡🌡'.'  feelslike'.$res->current->feelslike;
$weatherDescriptionsRow = 'Text description :'.$res->current->weather_descriptions[0];
$humidityRow = 'Humidity :'.$res->current->humidity.'  💧';
$windRow = 'Wind:'.$res->current->wind_speed.'   '.$res->current->wind_dir;

        return response()
            ->json($result);
    }
}