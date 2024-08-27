<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 22.08.2024
 * Time: 7:46
 */

namespace App\Services\BotCommands;


use App\Services\PhrasesService;
use Illuminate\Support\Facades\Log;

class WeatherForecastCommandHandler extends AbstractCommand
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
        $chatId    = $message['message']['chat']['id'];


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

        $locationRow            = "Location    :  ".$res->location->name." ".$res->location->region."  ⚓ 🏚🏚 🌈 💞" ;
        $timeZoneRow            = "Timezone    :  ".$res->location->timezone_id."  ".$res->location->localtime."  ⏲⏰🕰";
        $temperatureRow         = "Temperature :  ".$res->current->temperature."  🌡🌡🌡🌡  Feelslike :  ".$res->current->feelslike;
        $weatherDescriptionsRow = "Text description : ".$res->current->weather_descriptions[0]." 🆗🆗🆗";
        $humidityRow            = "Humidity    :  ".$res->current->humidity."  💧";
        $windRow                = "Wind        :  ".$res->current->wind_speed."m/s   Direction(не все поймут): ".$res->current->wind_dir." ♻️♻️♻️♻";

        $response = $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $locationRow."\n"."\n".$timeZoneRow."\n"."\n".$temperatureRow."\n"."\n".$weatherDescriptionsRow."\n"."\n".$humidityRow."\n"."\n".$windRow
        ]);

    }

}