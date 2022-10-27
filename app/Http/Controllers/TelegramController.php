<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use App\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected $buttons = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Button1',
                        'callback_data' => '1'
                    ],
                    [
                        'text' => 'Button2',
                        'callback_data' => '2'
                    ],
                    [
                        'text' => 'Help',
                        'callback_data' => '/help'
                    ]
                ]
            ]
        ];

    public function getData(Request $request){
        $keyboard = json_encode($this->buttons);
        $telegram = new Telegram;
        if (isset($request['message'])){
            $inMsg = $request['message'];
            if ($inMsg['text'] == '/start'){
                $telegramUser = TelegramUser::find($inMsg['from']['id']);
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
                        'text' => $outMsg,
                        'parse mode' => 'html',
                        'reply_markup' => $keyboard
                    ]);

                } else {
                    $outMsg = 'Hello ' . $telegramUser->first_name . '! ';
                    $telegram->request('sendMessage', [
                        'chat_id' => $telegramUser->telegram_id,
                        'text' => $outMsg,
                        'parse mode' => 'html',
                        'reply_markup' => $keyboard
                    ]);
                }
            }
        } else if (isset($request['callback_query'])){
            $callback = $request['callback_query'];
            if ($callback['data'] == '/help'){
                $outMsg = 'If a menu button other than MenuButtonDefault is set for a private chat, then it is applied in the chat. Otherwise the default menu button is applied. By default, the menu button opens the list of bot commands.';
                $telegram->request('sendMessage', [
                    'chat_id' => $callback['from']['id'],
                    'text' => $outMsg,
                    'parse mode' => 'html',
                    'reply_markup' => $keyboard
                ]);
            }
        }

    }
}
