<?php

use Illuminate\Support\Facades\Route;

Route::get('google/oauth', [\hanakivan\GoogleUserLogin\GoogleController::class, "oauth"])
    ->middleware([
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ])
    ->name("hanakivan.google.oauth");
