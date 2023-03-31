<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [ApiController::class, 'token']);
Route::get('/register-truffle', [ApiController::class, 'registerTruffle']);
