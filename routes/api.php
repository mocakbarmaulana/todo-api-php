<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    });
});

Route::group(["prefix" => "v1", "middleware" => "auth:api"], function () {
    Route::group(["prefix" => "auth"], function () {
        Route::post("logout", [\App\Http\Controllers\AuthController::class, "logout"]);
        Route::get("me", [\App\Http\Controllers\AuthController::class, "me"]);
    });

    Route::get("jokes", [\App\Http\Controllers\RandomJokeController::class, "get"]);
});
