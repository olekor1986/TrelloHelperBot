<?php

namespace App\Http\Controllers\TrelloApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function Symfony\Component\Translation\t;

class ListController extends Controller
{
    protected $api = 'https://api.trello.com/1/';

    protected $board_id = '634e84eeb84dbd02079873ef';

    protected $api_key = 'aa2ce6819a830179bf187fd045e316a5';

    protected $api_token = '0c56943f6e0f4a5fddb47b2da5af6428a76993d6108e77c573f83d67f6b6269a';

    public function createList($list_name){
        $url = $this->api . 'lists';
        $response = Http::post($url, [
            'name' => $list_name,
            'idBoard' => $this->board_id,
            'key' => $this->api_key,
            'token' => $this->api_token
        ]);
        return $response;
    }
}
