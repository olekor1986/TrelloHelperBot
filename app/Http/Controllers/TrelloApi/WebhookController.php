<?php

namespace App\Http\Controllers\TrelloApi;

use App\Http\Controllers\Controller;
use App\Models\TelegramGroup;
use App\Telegram\TrelloHelperBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function getTrelloUpdate(Request $request)
    {
        $telegramGroup = TelegramGroup::find('TrelloHelperGroup');
        $telegram = new TrelloHelperBot();

        if ($request['action']['display']['translationKey'] == 'action_added_list_to_board') {
            $listName = $request['action']['display']['entities']['list']['text'];
            $boardName = $request['action']['display']['entities']['board']['text'];
            $text = 'List "' . $listName . '" created on a ' . $boardName;
            $telegram->sendMessage($telegramGroup->id, $text);

        } else if ($request['action']['display']['translationKey'] == 'action_archived_list'){
            $listName = $request['action']['display']['entities']['list']['text'];
            $text = 'List "' . $listName . '" has been archived';
            $telegram->sendMessage($telegramGroup->id, $text);

        } else if ($request['action']['display']['translationKey'] == 'action_renamed_list'){
            $newListName = $request['action']['display']['entities']['list']['text'];
            $oldListName = $request['action']['display']['entities']['name']['text'];
            $text = 'List "' . $oldListName . '" has been renamed to "' . $newListName . '"';
            $telegram->sendMessage($telegramGroup->id, $text);

        } else if ($request['action']['display']['translationKey'] == 'action_create_card') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $listName = $request['action']['display']['entities']['list']['text'];
            $text = 'Card "' . $cardName . '" created in a ' . $listName;
            $telegram->sendMessage($telegramGroup->id, $text);

        } else if ($request['action']['display']['translationKey'] == 'action_move_card_from_list_to_list') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $listBefore = $request['action']['display']['entities']['listBefore']['text'];
            $listAfter = $request['action']['display']['entities']['listAfter']['text'];
            $text = 'Card "' . $cardName . '" has been moved from list "' . $listBefore . '" to list "' .
                $listAfter . '"';
            $telegram->sendMessage($telegramGroup->id, $text);

        } else if ($request['action']['display']['translationKey'] == 'action_archived_card') {
            $cardName = $request['action']['display']['entities']['card']['text'];
            $text = 'Card "' . $cardName . '" has been archived';
            $telegram->sendMessage($telegramGroup->id, $text);
        }
    }
}
