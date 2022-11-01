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
Route::any('webhook', [\App\Http\Controllers\WebhookController::class, 'getTelegramUpdate']);
Route::any('trello_callback', [\App\Http\Controllers\WebhookController::class, 'getTrelloUpdate']);
