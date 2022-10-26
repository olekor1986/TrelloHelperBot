<?php

use App\Telegram\Telegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    $telegram = new Telegram;
    $telegram->request('sendMessage', [
        'chat_id' => 620175323,
        'text' => 'OK'
    ]);
    dd($telegram);
});
*/
Route::any('tg_bot_input', [\App\Http\Controllers\TelegramController::class, 'getData']);
