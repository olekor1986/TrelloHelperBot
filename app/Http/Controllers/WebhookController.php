<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TrelloApi\ListController;
use App\Models\TelegramUser;
use App\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
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
        $buttons = json_encode($this->buttons);
        $telegram = new Telegram;
        if (isset($request['message']) && !isset($request['message']['reply_to_message'])) {
            $inMsg = $request['message'];
            if ($inMsg['text'] == '/start') {
                $telegramUser = TelegramUser::find($inMsg['from']['id']);
                if ($telegramUser === NULL) {
                    $newTelegramUser = TelegramUser::create($inMsg['from']);
                    $outMsg = 'Hello ' . $newTelegramUser->first_name . '! ' . 'You have been added to the database.';
                    $telegram->sendButtons($newTelegramUser->id, $outMsg, $buttons);
                } else {
                    $outMsg = 'Hello ' . $telegramUser->first_name . '! ' . 'You are already in the database.';
                    $telegram->sendButtons($telegramUser->id, $outMsg, $buttons);
                }
            }
        } else if (isset($request['callback_query'])) {
            $callback = $request['callback_query'];
            if ($callback['data'] == '/help') {
                $outMsg = 'The bot is designed to help you create Trello lists and track card movement.';
                $telegram->sendButtons($callback['from']['id'], $outMsg, $buttons);
            } else if($callback['data'] == '/create_list') {
                $telegram->forceReply($callback['from']['id'], 'Enter list name:');
            }
        } else if (isset($request['message']['reply_to_message'])){
            if($request['message']['reply_to_message']['text'] == 'Enter list name:'){
                $list_name = $request['message']['text'];
                (new TrelloApi\ListController)->createList($list_name);
            }
        }
    }
    public function getTrelloUpdate(Request $request)
    {
        $telegram = new Telegram();
        $telegramUsers = TelegramUser::all();
        if ($request['action']['display']['translationKey'] == 'action_added_list_to_board') {
            $listName = $request['action']['display']['entities']['list']['text'];
            $boardName = $request['action']['display']['entities']['board']['text'];
            $text = 'List "' . $listName . '" created on a ' . $boardName;
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        } else if ($request['action']['display']['translationKey'] == 'action_archived_list'){
            $listName = $request['action']['display']['entities']['list']['text'];
            $text = 'List "' . $listName . '" has been archived';
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        } else if ($request['action']['display']['translationKey'] == 'action_renamed_list'){
            $newListName = $request['action']['display']['entities']['list']['text'];
            $oldListName = $request['action']['display']['entities']['name']['text'];
            $text = 'List "' . $oldListName . '" has been renamed to "' . $newListName . '"';
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        } else if ($request['action']['display']['translationKey'] == 'action_create_card') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $listName = $request['action']['display']['entities']['list']['text'];
            $text = 'Card "' . $cardName . '" created in a ' . $listName;
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        } else if ($request['action']['display']['translationKey'] == 'action_move_card_from_list_to_list') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $listBefore = $request['action']['display']['entities']['listBefore']['text'];
            $listAfter = $request['action']['display']['entities']['listAfter']['text'];
            $text = 'Card "' . $cardName . '" has been moved from list "' . $listBefore . '" to list "' .
                $listAfter . '"';
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        } else if ($request['action']['display']['translationKey'] == 'action_archived_card') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $text = 'Card "' . $cardName . '" has been archived';
            foreach ($telegramUsers as $telegramUser) {
                $telegram->sendMessage($telegramUser->id, $text);
            }
        }
    }
}
