<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('{bot_token}/webhook', [\App\Http\Controllers\TelegramController::class, 'webhook']);
