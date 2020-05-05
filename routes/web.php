<?php

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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/users', 'Admin\UsersController@index');
Route::get('/admin/channels', 'Admin\GracenoteController@index');//gracenote
Route::get('/admin/channelstatus', 'Admin\ChannelController@index');//channel
Route::get('/admin/channelstatus/view/{id}', 'Admin\ChannelController@show');//channel
Route::get('/admin/channelstatus/edit/{id}', 'Admin\ChannelController@edit');//channel

Route::get('/admin/users/create', 'Admin\UsersController@create');
Route::get('/admin/channelstatus/create', 'Admin\ChannelController@create');//channels Statust
Route::get('/admin/channels/create', 'Admin\GracenoteController@create');//gracenote
Route::get('/admin/channels/view/{id}', 'Admin\GracenoteController@show');//gracenote
Route::get('/admin/channels/edit/{id}', 'Admin\GracenoteController@edit');//gracenote
Route::get('/admin/users/view/{id}', 'Admin\UsersController@show');
Route::get('/admin/users/edit/{id}', 'Admin\UsersController@edit');
Route::get('/admin/subscriptions/view/{id}','Admin\SubscriptionController@show');
Route::get('/admin/subscriptions/edit/{id}','Admin\SubscriptionController@edit');
Route::get('changeStatus', 'Admin\SubscriptionController@changeStatus');
Route::get('/admin/subscriptionpack', 'Admin\SubscriptionPackController@index');
Route::get('/admin/subscriptionpack/view/{id}', 'Admin\SubscriptionPackController@show');
Route::get('/admin/subscriptionpack/edit/{id}', 'Admin\SubscriptionPackController@edit');


// Route::resource('/admin/users', 'Admin\UsersController');
Route::get('profileshow/{id}', 'Admin\UsersController@show')->name('profileshow');//for user to show info
Route::put('profile/{id}','Admin\UsersController@update')->name('profileupdate');// for user to update info
Route::middleware(['auth'])->prefix('admin')->name('admin')->group(function(){
    Route::resource('subscriptions', 'Admin\SubscriptionController');
    Route::post('insertquotation','Admin\SubscriptionController@store')->name('insertquotation');
    Route::post('insertuser','Admin\UsersController@store')->name('insertuser');
    Route::delete('subscriptions/destroy/{id}','Admin\SubscriptionController@destroy');
    Route::put('userupdate/{id}','Admin\UsersController@update')->name('userupdate');

    Route::put('subscriptionupdate/{id}','Admin\SubscriptionController@update')->name('subscriptionupdate');
    Route::put('subscriptionpackupdate/{id}','Admin\SubscriptionPackController@update')->name('subscriptionpackupdate');
    // admi routes
Route::delete('users/destroy/{id}', 'Admin\UsersController@destroy');
   // Route::get('view/{id}','Admin\SubscriptionController@show');

  
    // Zipcode Routes
    Route::get('zipcode', 'ZipcodeController@index');
    Route::get('/zipcode/view/{id}', 'ZipcodeController@show');
    Route::post('zipcode/update', 'ZipcodeController@updateRole');
    Route::delete('role/{id}/delete', 'ZipcodeController@deleteRole');

});