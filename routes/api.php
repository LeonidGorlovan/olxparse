<?php

use App\Http\Controllers\Api\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe', SubscribeController::class);
