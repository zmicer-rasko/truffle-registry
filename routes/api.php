<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TruffleController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [AuthController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/register-truffle', [TruffleController::class, 'registerTruffle']);
});