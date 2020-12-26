<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/checkVerifyCode', [AuthController::class, 'checkVerifyCode']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user-profile', [AuthController::class, 'userProfile']);


    Route::get('/users', [UsersController::class, 'users']);
    Route::post('/saveUser', [UsersController::class, 'saveUser']);
    Route::post('/show/{id}', [UsersController::class, 'show']);
    Route::post('/friends', [FriendsController::class, 'friends']);
    Route::post('/myRequests', [FriendsController::class, 'myRequests']);
    Route::post('/myRequests/accept', [FriendsController::class, 'accept']);
    Route::post('/myRequests/decline', [FriendsController::class, 'decline']);
    Route::post('/myFriends', [FriendsController::class, 'myFriends']);
    Route::post('/delete', [FriendsController::class, 'delete']);
    Route::post('/deleteMessage', [ChatsController::class, 'deleteMessage']);

    Route::prefix('/chat')->middleware(["auth"])->group(function () {
        Route::post('/my_message',[ChatsController::class,'my_message']);
        Route::post('/send',[ChatsController::class,'send']);
    });
});

//Route::prefix('/')->middleware(['auth'])->group(function () {
//
//});
