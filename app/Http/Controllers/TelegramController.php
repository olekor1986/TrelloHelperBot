<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use App\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{

    public function getData(Request $request){
        if (isset($request['message'])){
            $telegram = new Telegram;
            $inMsg = $request['message'];
            if ($inMsg['text'] = '/start'){
                $telegramUser = TelegramUser::find($inMsg['from']['id']);
                Log::info(print_r($telegramUser, true));
                if($telegramUser === NULL){
                    $newTelegramUserData['telegram_id'] = $inMsg['from']['id'];
                    $newTelegramUserData['is_bot'] = $inMsg['from']['is_bot'];
                    $newTelegramUserData['first_name'] = $inMsg['from']['first_name'];
                    if(isset($inMsg['from']['last_name'])){
                        $newTelegramUserData['last_name'] = $inMsg['from']['last_name'];
                    }
                    $newTelegramUserData['username'] = $inMsg['from']['username'];
                    $newTelegramUserData['language_code'] = $inMsg['from']['language_code'];

                    $newTelegramUser = TelegramUser::create($newTelegramUserData);
                    $outMsg = 'Hello ' . $newTelegramUser->first_name . '! ';

                    $telegram->request('sendMessage', [
                        'chat_id' => $newTelegramUser->telegram_id,
                        'text' => $outMsg
                    ]);

                } else {
                    $outMsg = 'Hello ' . $telegramUser->first_name . '! ';
                    $telegram->request('sendMessage', [
                        'chat_id' => $telegramUser->telegram_id,
                        'text' => $outMsg
                    ]);
                }
            }

        }

    }
}
