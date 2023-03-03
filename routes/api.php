<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('signup', 'App\Http\Controllers\AuthController@signup');
Route::group([
    'prefix' => 'auth'
], function () {
    // Route::post('login', 'AuthController@login');
    
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('signup', 'App\Http\Controllers\AuthController@signup');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
