<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request $request
     * @return Response
     */
    public function getInfo(Request $request)
    {
        Log::info('Showing the user profile for user: {id}', ['id' => $request->toArray()]);

      ///  file_put_contents('qwe.txt',var_export($request->toArray()),FILE_APPEND);

       return response()->json(['name' => 'Abigail', 'state' => 'CA']);

        //https://api.telegram.org/bot6967376895:AAEGSoh5qp1kDyEHixB5-CoTe1WVmDikLTA/getWebhookInfo

    }
}