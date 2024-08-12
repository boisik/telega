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
use Longman\TelegramBot\Telegram;

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

        $bot_api_key  = 'your:bot_api_key';
        $bot_username = 'username_bot';

        $mysql_credentials = [
            'host'     => 'localhost',
            'port'     => 3306, // optional
            'user'     => 'admin',
            'password' => 'admin',
            'database' => 'telegabot',
        ];

        $telegram = new Api('6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA');

// Example usage
        $updates = $telegram->getUpdates();
dd($updates);

    }
}
