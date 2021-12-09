<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users/all', [UserController::class, 'all'])->name('users.all');

Route::get('/users/buscar/{id}', [UserController::class, 'buscar'])->name('users.buscar');

Route::post('/users/createdAt', [UserController::class, 'createdAt'])->name('users.createdAt');

Route::post('/users/betweenCreatedAt', [UserController::class, 'betweenCreatedAt'])->name('users.betweenCreatedAt');

Route::resource('users',UserController::class,[ 'except'=>['create','edit'] ]);

Route::resource('profiles',ProfileController::class,[ 'except'=>['create','edit','show','store'] ]);



