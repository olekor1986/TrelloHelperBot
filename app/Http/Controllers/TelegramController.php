<?php

namespace App\Http\Controllers;

use App\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function getData(Request $request){
        //$data = file_get_contents('php://input');
        Log::debug($request);

        $telegram = new Telegram;
        $telegram->request('sendMessage', [
            'chat_id' => 620175323,
            'text' => $request['message']['text']
        ]);
    }
}
