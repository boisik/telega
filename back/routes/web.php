<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Laravel\Facades\Telegram;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post('/webhook', 'TelegramController@getInfo');

$router->post('/webhookddd', function () {

    $config = [
        'bots' => [
            'mybot' => [
                'token' => '6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA',
            ],
        ]
    ];

    $telegram = new BotsManager($config);
    $response=  $telegram->bot('mybot')->getWebhookUpdate();



    file_put_contents('qwe.txt',var_export($response),FILE_APPEND);

    return 'ok';
});

