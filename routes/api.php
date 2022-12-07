<?php

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

Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'AuthController@logout');
        Route::resource('product', ProductController::class );
        Route::apiResource('payment', PaymentMethodContoller::class);
    });
});
