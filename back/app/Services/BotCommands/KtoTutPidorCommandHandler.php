<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 22.08.2024
 * Time: 7:46
 */

namespace App\Services\BotCommands;

use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class KtoTutPidorCommandHandler
{

    /**
     * @var TelegramService
     */
    private $telegramService;

    public function __construct(
        TelegramService  $telegramService
    )
    {
        $this->telegramService = $telegramService;
    }


    public function execute()
    {
        Log::info(get_class($this).' execute');

    }

}