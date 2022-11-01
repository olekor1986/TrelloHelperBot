<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TrelloApi\ListController;
use App\Models\TelegramGroup;
use App\Models\TelegramUser;
use App\Telegram\TrelloHelperBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrelloHelperBotController extends Controller
{
    protected $buttons = [
        'inline_keyboard' => [
            [
                [
                    'text' => 'Create List',
                    'callback_data' => '/create_list'
                ],
                [
                    'text' => 'Help',
                    'callback_data' => '/help'
                ]
            ]
        ]
    ];

    public function getTelegramUpdate(Request $request)
    {
        $bot = new TrelloHelperBot;
        $buttons = json_encode($this->buttons);
        if (isset($request['message'])) {
            $message = $request['message'];
            $user_id = $message['from']['id'];
            if ($message['chat']['type'] == 'private') {
                if (isset($message['reply_to_message'])) {
                    if ($message['reply_to_message']['text'] == 'Enter list name:') {
                        $list_name = $message['text'];
                        $list = (new TrelloApi\ListController)->createList($list_name);
                        return $list;
                    }
                }
                if ($message['text'] == '/start') {
                    $telegramUser = TelegramUser::find($user_id);
                    if ($telegramUser === NULL) {
                        $newTelegramUser = TelegramUser::create($message['from']);
                        $outMsg = 'Hello ' . $newTelegramUser->first_name . '! ' . 'You have been added to the database.';
                        $bot->sendButtons($user_id, $outMsg, $buttons);
                    } else {
                        $outMsg = 'Hello ' . $telegramUser->first_name . '! ' . 'You are already in the database.';
                        $bot->sendButtons($user_id, $outMsg, $buttons);
                    }
                }
            } else if ($message['chat']['type'] == 'group') {
                $chat_id = $message['chat']['id'];
                $title = $message['chat']['title'];
                if ($message['text'] == '/start') {
                    $telegramGroup = TelegramGroup::find($chat_id);
                    if ($telegramGroup === NULL){
                        $newTelegramGroup = [
                            'id' => $chat_id,
                            'title' => $title
                        ];
                        TelegramGroup::create($newTelegramGroup);
                    }
                    $telegramUser = TelegramUser::find($user_id);
                    if ($telegramUser === NULL) {
                        $newTelegramUser = TelegramUser::create($message['from']);
                        $outMsg = 'Hello ' . $newTelegramUser->first_name . '! ' . 'You have been added to the database.';
                        $bot->sendMessage($chat_id, $outMsg);
                    } else {
                        $outMsg = 'Hello ' . $telegramUser->first_name . '! ' . 'You are already in the database.';
                        $bot->sendMessage($chat_id, $outMsg);
                    }
                }
            }
        } else if (isset($request['callback_query'])) {
            $callback = $request['callback_query'];
            if ($callback['data'] == '/help') {
                $outMsg = 'The bot is designed to help you create Trello lists and track card movement.';
                $bot->sendButtons($callback['message']['chat']['id'], $outMsg, $buttons);
            } else if ($callback['data'] == '/create_list') {
                $bot->forceReply($callback['message']['chat']['id'], 'Enter list name:');
            }
        }
    }
}
