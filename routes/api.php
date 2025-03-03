<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(CheckTokenMiddleware::class)->post('/users', [UserController::class, 'create']);

Route::get('/users', [UserController::class, 'list']);

Route::get('/users/{id}', [UserController::class, 'find']);

Route::get('/positions', [PositionController::class, 'list']);

Route::get('/token', [TokenController::class, 'getToken']);
