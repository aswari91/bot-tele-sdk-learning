<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    $response = Telegram::bot('mybot');
    $response = $response->getUpdates([
        'offset' => -1,
        'limit' => 1,
        'timeout' => 0
    ]);
    dd($response);

    // return view('welcome');
});
