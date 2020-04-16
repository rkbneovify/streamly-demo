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

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'APIController@logout');

    Route::get('tasks', 'SessionController@index');
    Route::get('tasks/{id}', 'SessionController@show');
    Route::post('tasks', 'SessionController@store');
    Route::put('tasks/{id}', 'SessionController@update');
    Route::delete('tasks/{id}', 'SessionController@destroy');
});