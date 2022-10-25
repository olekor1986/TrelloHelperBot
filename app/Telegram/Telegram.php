<?php

namespace App\Telegram;

class Telegram
{
    protected $url = 'https://api.telegram.org/bot';

    protected $api_token = '5760643947:AAEEpMT2t8xnf7A_JiI3u_V5u97TuIZHYtk';

    public function request(string $tgMethodName, array $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url . $this->api_token .  '/' . $tgMethodName);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $out = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $out;
    }


}
