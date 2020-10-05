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


Route::get('search', 'HomeController@search')->name('search');
Route::get('autocomplete', 'HomeController@autocomplete')->name('autocomplete');






Route::middleware(['auth','role:superadmin|admin'])->prefix('admin')->name('admin')->group(function(){
    
    
    Route::post('insertsubscriptionpack','Admin\SubscriptionPackController@store')->name('insertsubscriptionpack');
    
    //subscription 
    Route::resource('subscriptions', 'Admin\SubscriptionController');
     Route::get('/subscriptions/view/{id}','Admin\SubscriptionController@show');
   Route::get('/subscriptions/edit/{id}','Admin\SubscriptionController@edit');
    Route::get('changeStatus', 'Admin\SubscriptionController@changeStatus');
    Route::post('insertquotation','Admin\SubscriptionController@store')->name('insertquotation');
    Route::put('subscriptionupdate/{id}','Admin\SubscriptionController@update')->name('subscriptionupdate');
    Route::delete('subscriptiondestroy/{id}','Admin\SubscriptionController@destroy')->name('subscriptiondestroy');;


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

    Route::get('/subscriptionpack/plans', 'Admin\SubscriptionPackController@viewPlan');
    // User routes
    Route::get('/users', 'Admin\UsersController@index');
    Route::get('user/data', 'Admin\UsersController@UserAjaxData');
    Route::get('/users/session', 'Admin\UsersController@activeSession');//for user session
    Route::get('/users/connected/{id}', 'Admin\UsersController@connecteddevice');//connected devices
    Route::get('/users/create', 'Admin\UsersController@create');
    Route::post('insertuser','Admin\UsersController@store')->name('insertuser');
    Route::get('/users/view/{id}', 'Admin\UsersController@show');
Route::get('/users/edit/{id}', 'Admin\UsersController@edit');
Route::put('userupdate/{id}','Admin\UsersController@update')->name('userupdate');
Route::delete('users/destroy/{id}', 'Admin\UsersController@destroy');
Route::delete('users/sessiondestroy/{id}', 'Admin\UsersController@sessiondestroy')->name('users.sessiondestroy');


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
    Route::get('allowedzipcode', 'ZipcodeController@allowedZipcodes');
    Route::get('/zipcode/create', 'ZipcodeController@create');
    Route::get('/zipcode/view/{id}', 'ZipcodeController@show');
    Route::get('/zipcode/edit/{id}', 'ZipcodeController@edit');
    //Route::post('zipcode/update', 'ZipcodeController@updateRole');
    Route::post('insertzipcode','ZipcodeController@store')->name('insertzipcode');
    Route::put('zipcodeupdate/{id}','ZipcodeController@update')->name('zipcodeupdate');
    Route::delete('zipcode/destroy', 'ZipcodeController@massDestroy')->name('zipcode.massDestroy');
    Route::get('zipcode/category', 'ZipcodeController@categoryData');
    
    Route::get('zipcode/allowedzipdata', 'ZipcodeController@allowedZipdata');
    Route::get('zipcode/map', 'ZipcodeController@map');
Route::get('zipcode/checkfenceweb', 'ZipcodeController@webfenceturf');

Route::get('channels/gracenote', 'Admin\GracenoteController@gracenoteData');
    Route::delete('zipcode/{id}/delete', 'ZipcodeController@deleteRole');
    
 //hls anaylzer

Route::get('/hlsmonitor', 'VideoAnylyzerController@test2');
Route::get('/frontendtext/create', 'Admin\FrontendTextController@create');
Route::get('/frontendtext', 'Admin\FrontendTextController@index');//frontendtext
Route::post('insertfrontendtext','Admin\FrontendTextController@store')->name('insertfrontendtext');
Route::put('fronttextupdate','Admin\FrontendTextController@update')->name('fronttextupdate');
});

Route::get('add-to-log', 'HomeController@myTestAddToLog');

Route::get('logActivity', 'HomeController@logActivity');




Route::get('/logout', 'HomeController@logout')->name('logout');
Route::get('/login/google', 'HomeController@rediretToGoogleLogin')->name('google.login');

Route::get('/google/login', 'HomeController@handleGoogleCallback');
Route::post('/user/register', 'HomeController@createUser');
Route::post('/user/contactformsend', 'HomeController@contactformsend');

Route::post('/forgotpassword', 'Auth\ForgotPasswordController@webgetResetToken');
Route::get('/invoice', 'Pages\Account\Setting@invoice');
Route::get('user/invoice/{invoice}','Pages\Account\Setting@invoicePDF' );
Route::get('/payment/{stripeplanid}', 'Pages\Payment@showPage');
Route::get('/selectplan', 'Pages\Payment@showPlan');
Route::get('/livetv', 'Pages\LiveTv@showPage');
Route::get('/currentepisode', 'Pages\LiveTv@getcurrentepisode');
Route::get('/livetv/list', 'Pages\LiveTv@showAllPage');
Route::get('/livetv/recent', 'Pages\LiveTv@showRecent');
Route::get('/livetv/{channel}/{genre}', 'Pages\LiveTv@showChannel');
Route::get('/videojs', 'Pages\LiveTv@videojs');
Route::get('/videojs2', 'Pages\LiveTv@videojs2');
Route::get('/faq', 'Pages\Faq@showPage');
Route::get('/termsofuse', 'Pages\Faq@termsofuse');
Route::get('/privacy', 'Pages\Faq@privacy');

Route::get('/account/settings', 'Pages\Account\Setting@showSettingpage');
Route::get('/account/settings/bug', 'Pages\Account\Setting@BugReportView');
Route::post('/sendbugreport', 'Pages\Account\Setting@sendBugReport')->name('sendbugreport');
Route::get('/verified', 'HomeController@verified');
Route::get('/checkAvailabilty', 'HomeController@checkAvailabilty');
Route::get('/contactform', 'HomeController@contactform');
Route::get('/devices', 'HomeController@showSupportedDevices');
Route::get('/helpandsupport', 'HomeController@helpandsupp');
Route::delete('user/sessiondestroy/{id}', 'Pages\Account\Setting@sessiondestroy')->name('user.sessiondestroy');
Route::delete('user/subscriptiondestroy/{id}','Pages\Account\Setting@destroy')->name('user.subscriptiondestroy');

Route::put('userupdate/{id}','Pages\Account\Setting@update')->name('userupdate');
Route::post('/insertpay/{StripeplanId}','Pages\Payment@pay')->name('insertpay');//send form data here
Route::post('favorite/', 'Pages\LiveTv@favoriteChannel');
Route::post('unfavorite/', 'Pages\LiveTv@unFavoriteChannel');
Route::get('my_favorites', 'Pages\LiveTv@myFavorites');


Route::get('/ffmpeg', 'VideoAnylyzerController@index');
Route::get('/ffmpeg2', 'VideoAnylyzerController@probeAudioFile');
Route::get('/ffmpeg3', 'VideoAnylyzerController@testFprobe');
Route::get('/ffmpeg4', 'VideoAnylyzerController@testProbeOnRemoteFile');
Route::get('/epgviewr', 'Pages\LiveTv@epgScreen');
Route::get('/epgviewr2', 'Pages\LiveTv@epgScreen2');


Route::get('/drm', 'Admin\BuyDRMController@WidevinePlayer');
Route::get('/drm2', 'Admin\BuyDRMController@WidevinePlayer2');
Route::get('/ism', 'Admin\BuyDRMController@WidevinePlayer3');
Route::get('/fair', 'Admin\BuyDRMController@fairPlayer');
Route::get('/fair2', 'Admin\BuyDRMController@fairPlayer2');

Route::get('/react', 'Pages\LiveTv@webReact');
Route::get('ajax-check', 'Admin\SubscriptionController@ajaxCheck')->name('ajax-check');//check coupon
Route::get('/stripesync', function (Request $request) {
    Artisan::call('stripe:sync-renewals');
    
    return redirect('admin/subscriptions')->with('info',Artisan::output());
 });