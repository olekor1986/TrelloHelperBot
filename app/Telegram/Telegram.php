<?php

namespace App\Telegram;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected $api = 'https://api.telegram.org/bot';

    protected $api_token = '5760643947:AAEEpMT2t8xnf7A_JiI3u_V5u97TuIZHYtk';

    public function sendMessage(string $chat_id, string $text){
        $this->request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse mode' => 'html'
        ]);
    }

    public function updateMessage(string $chat_id,  string $message_id, string $text){
        $this->request('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse mode' => 'html'
        ]);
    }

    public function forceReply(string $chat_id, string $text){
        $this->request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse mode' => 'html',
            'reply_markup' => json_encode([
                'force_reply' => true,
                'selective' => true
            ])
        ]);
    }

    public function sendButtons(string $chat_id, string $text, string $buttons){
        $this->request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse mode' => 'html',
            'reply_markup' => $buttons
        ]);
    }

    public function updateButtons(string $chat_id, string $message_id, string $text, string $buttons){
        $this->request('sendMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse mode' => 'html',
            'reply_markup' => $buttons
        ]);
    }

    public function request(string $tgMethodName, array $data) {
        $url = $this->api . $this->api_token . '/' . $tgMethodName;
        $response = Http::post($url, $data);
        return $response;
    }


}
