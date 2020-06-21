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

Route::get('/admin', 'Admin\AdminController@index');
Route::get('/', 'HomeController@index')->name('home');







Route::middleware(['auth','role:superadmin|admin'])->prefix('admin')->name('admin')->group(function(){
    
    
    Route::post('insertsubscriptionpack','Admin\SubscriptionPackController@store')->name('insertsubscriptionpack');
    
    //subscription 
    Route::resource('subscriptions', 'Admin\SubscriptionController');
     Route::get('/subscriptions/view/{id}','Admin\SubscriptionController@show');
   Route::get('/subscriptions/edit/{id}','Admin\SubscriptionController@edit');
    Route::get('changeStatus', 'Admin\SubscriptionController@changeStatus');
    Route::post('insertquotation','Admin\SubscriptionController@store')->name('insertquotation');
    Route::put('subscriptionupdate/{id}','Admin\SubscriptionController@update')->name('subscriptionupdate');
    Route::delete('subscriptions/destroy/{id}','Admin\SubscriptionController@destroy');
    //gracenote
    Route::get('/channels', 'Admin\GracenoteController@index');//gracenote
    Route::get('/channels/create', 'Admin\GracenoteController@create');//gracenote
Route::get('/channels/view/{id}', 'Admin\GracenoteController@show');//gracenote
Route::get('/channels/edit/{id}', 'Admin\GracenoteController@edit');//gracenote
    Route::put('channelupdate/{id}','Admin\GracenoteController@update')->name('channelupdate');
    //subscription pack
     Route::get('/subscriptionpack', 'Admin\SubscriptionPackController@index');
    Route::get('/subscriptionpack/view/{id}', 'Admin\SubscriptionPackController@show');
    Route::get('/subscriptionpack/edit/{id}', 'Admin\SubscriptionPackController@edit');
     Route::get('/subscriptionpack/create', 'Admin\SubscriptionPackController@create');
    Route::put('subscriptionpackupdate/{id}','Admin\SubscriptionPackController@update')->name('subscriptionpackupdate');
    Route::delete('subscriptionpack/destroy/{id}','Admin\SubscriptionPackController@destroy');
    // User routes
    Route::get('/users', 'Admin\UsersController@index');
    Route::get('/users/create', 'Admin\UsersController@create');
    Route::post('insertuser','Admin\UsersController@store')->name('insertuser');
    Route::get('/users/view/{id}', 'Admin\UsersController@show');
Route::get('/users/edit/{id}', 'Admin\UsersController@edit');
Route::put('userupdate/{id}','Admin\UsersController@update')->name('userupdate');
Route::delete('users/destroy/{id}', 'Admin\UsersController@destroy');


  //channel
  Route::get('/channelstatus/create', 'Admin\ChannelController@create');//channels Statust
  
Route::get('/channelstatus', 'Admin\ChannelController@index');//channel
Route::get('/channelstatus/view/{id}', 'Admin\ChannelController@show');//channel
Route::get('/channelstatus/edit/{id}', 'Admin\ChannelController@edit');//channel
Route::get('channelstatus/channeldata', 'Admin\ChannelController@channelData');//ajax channel data
Route::post('insertchannel','Admin\ChannelController@store')->name('insertchannel');
Route::put('channelupdate/{id}','Admin\ChannelController@update')->name('channelupdate');
    // Zipcode Routes
    Route::get('zipcode', 'ZipcodeController@index');
    Route::get('/zipcode/view/{id}', 'ZipcodeController@show');
    Route::post('zipcode/update', 'ZipcodeController@updateRole');
    Route::delete('zipcode/destroy', 'ZipcodeController@massDestroy')->name('zipcode.massDestroy');
    Route::get('zipcode/category', 'ZipcodeController@categoryData');

Route::get('channels/gracenote', 'Admin\GracenoteController@gracenoteData');
    Route::delete('zipcode/{id}/delete', 'ZipcodeController@deleteRole');
    
 //hls anaylzer

Route::get('/ffmpeg6', 'VideoAnylyzerController@test2');
});




Route::get('/logout', 'HomeController@logout')->name('logout');
Route::get('/login/google', 'HomeController@rediretToGoogleLogin')->name('google.login');

Route::get('/google/login', 'HomeController@handleGoogleCallback');
Route::post('/user/register', 'HomeController@createUser');

Route::get('/payment', 'Pages\Payment@showPage');
Route::get('/livetv', 'Pages\LiveTv@showPage');
Route::get('/livetv/list', 'Pages\LiveTv@showAllPage');
Route::get('/livetv/{channel}', 'Pages\LiveTv@showChannel');
Route::get('/faq', 'Pages\Faq@showPage');

Route::get('/account/settings', 'Pages\Account\Setting@showSettingpage');

Route::post('/insertpay','Pages\Payment@pay')->name('insertpay');//send form data here

Route::get('/ffmpeg', 'VideoAnylyzerController@index');
Route::get('/ffmpeg2', 'VideoAnylyzerController@probeAudioFile');
Route::get('/ffmpeg3', 'VideoAnylyzerController@testFprobe');
Route::get('/ffmpeg4', 'VideoAnylyzerController@testProbeOnRemoteFile');
Route::get('/ffmpeg5', 'VideoAnylyzerController@test');