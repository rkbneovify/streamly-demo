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

Route::get('/', function (Request $request) {
    return 'Working';
});

// For api Registration, confirmations and verification
Route::get('/front', 'APIController@index')->name('front');
Route::post('/frontlogin', 'APIController@login')->name('frontlogin');
Route::post('/frontlogout/{id}', 'APIController@logout')->name('frontlogout');



// Registration, confirmations and verification
Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('auth/register', 'Auth\AuthController@register');
Route::get('auth/verify', 'Auth\AuthController@verify');


// Authentication Routes
Route::group(['prefix' => 'auth'], function ($router) {
    
    Route::post('login', 'Auth\AuthController@login');
    Route::post('change-password', 'Auth\AuthController@changePassword');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::get('me', 'Auth\AuthController@me');
    Route::get('profile', 'Auth\AuthController@profile');
    Route::put('updateprofile','Auth\AuthController@updateprofile');
       //zipcodes
       Route::get('zipcode', 'ZipcodeController@zipjson');
       //channel api

    Route::get('me', 'Admin\ChannelController@channellist');
});

// Superadmin Routes
Route::group(['middleware' => ['jwt.verify','role:superadmin|admin'],'prefix' => 'sadmin','namespace'=>'Admin'], function($router)
{	
  
    // User Routes
    Route::post('user','UserAndRoleController@createUser');
    Route::post('user/update', 'UserAndRoleController@updateUser');
    Route::get('users', 'UserAndRoleController@getUsers');
    Route::get('channels', 'GracenoteController@getChannel');
    Route::post('users/dt', 'UserAndRoleController@getDTUsers');
    Route::get('user/{id}', 'UserAndRoleController@getUser');    
    Route::delete('user/{id}/delete', 'UserAndRoleController@deleteUser');
    Route::get('user/{id}/reset-password', 'UserAndRoleController@resetPassword');

    Route::post('user/role/assign', 'UserAndRoleController@assignRole');
    Route::get('user/role/{id}', 'UserAndRoleController@getUserRole');

    // Role Routes
    Route::post('role', 'UserAndRoleController@createRole');
    Route::post('role/update', 'UserAndRoleController@updateRole');
    Route::get('roles', 'UserAndRoleController@getRoles');
    Route::post('roles/dt', 'UserAndRoleController@getDTRoles');
    Route::get('role/{id}', 'UserAndRoleController@getRole');
    Route::delete('role/{id}/delete', 'UserAndRoleController@deleteRole');

    
});



// Route::fallback(function(){
//     return response()->json([
//         'response_code'=>404,
//         'message' => 'Page Not Found or Wrong method used in postman. If error persists, contact info@website.com'], 404);
// });