<?php

use Illuminate\Support\Facades\Route;

Route::get('google/oauth', [\hanakivan\GoogleUserLogin\GoogleController::class, "oauth"])->name("hanakivan.google.oauth");
