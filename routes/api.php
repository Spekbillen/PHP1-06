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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('{model?}', [\App\Http\Controllers\ApiController::class, 'index']);
Route::post('{model?}', [\App\Http\Controllers\ApiController::class, 'store']);
Route::get('{model?}/{id?}', [\App\Http\Controllers\ApiController::class, 'show']);

//Route::apiResource('products', \App\Http\Controllers\ApiController::class,);
