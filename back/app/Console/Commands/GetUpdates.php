<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;



use Exception;
use Illuminate\Console\Command;

use Telegram\Bot\Api;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class GetUpdates extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "gu";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "get updates";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');
        //$response = $telegram->setWebhook(['url' => 'https://da0a-185-230-143-47.ngrok-free.app/6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/webhook']);

// Example usage
        $response = $telegram->sendMessage([
            'chat_id' => '2050824714',
            'text' => 'Hello World'

        ]);
dd($response);

    }
}
