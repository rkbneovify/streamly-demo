<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'APIController@login');
Route::post('register', 'APIController@register');
Route::post('logout/{id}', 'APIController@logout');//add this later in middle ware wen viewgets created
Route::post('channel', 'ChannelController@store');
Route::put('channel/{id}', 'ChannelController@update');
Route::delete('channel/{id}', 'ChannelController@destroy');

Route::post('subscription', 'SubscriptionController@store');
Route::put('subscription/{id}', 'SubscriptionController@update');


Route::group(['middleware' => 'auth.jwt'], function () {
    
   //session table will be hidden user dosen't need access to it
    // Route::get('tasks', 'SessionController@index');
    // Route::get('tasks/{id}', 'SessionController@show');
    // Route::post('tasks', 'SessionController@store');
    // Route::put('tasks/{id}', 'SessionController@update');
    // Route::delete('tasks/{id}', 'SessionController@destroy');
});