<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
|                              Karma Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/')->group(function () {
    Route::get('user/{id}/karma-position/{numUsers?}', [UserController::class, 'getOverall'])
        ->where('numUsers', '[0-9]+');
});
