<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/call-sync-http', "App\Http\Controllers\CallAsyncHttpApiController@callSyncHttp");
Route::get('/spatie-sync-http', "App\Http\Controllers\AsyncSpatieController@callAsyncAPI");
Route::get('/revolt-test-query', "App\Http\Controllers\RevoltController@testQuery");


