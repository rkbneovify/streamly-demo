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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', function (Request $request) {
    return 'Working';
});
// For api Registration, confirmations and verification
Route::get('/front', 'APIController@index')->name('front');
Route::get('/unhash/{id}', 'APIController@unhashid')->name('unhashid');
Route::post('/frontlogin', 'APIController@login')->name('frontlogin');
Route::post('/frontlogout/{id}', 'APIController@logout')->name('frontlogout');

//QR code visibility API
Route::get('auth/qrvisibility', 'APIController@qrvisibility');
Route::post('auth/connecteddevicelist', 'APIController@connecteddeviceemail');
Route::delete('auth/userdelete/{id}', 'APIController@userdelete');

// Registration, confirmations and verification
Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('auth/register', 'Auth\AuthController@register');
Route::get('auth/verify', 'Auth\AuthController@verify')->middleware(['web']);
// Route::post('auth/location', 'HomeController@location');
Route::post('auth/oldgeofence', 'HomeController@oldgeofence');
// Route::post('auth/geofence', 'HomeController@geofence');
Route::post('auth/runquery', 'HomeController@simplequeryrun');
Route::get('auth/allowed', 'HomeController@allowedZipcode');
Route::post('auth/geofence', 'Admin\TurfController@index')->middleware(['jwt.verify']);
Route::post('auth/geofence2', 'Admin\TurfController2@index')->middleware(['jwt.verify']); //refactored

/** Angular Frontend APIs code start here */

//Get all stripe plans
Route::get('/stripe/plans', 'HomeController@getAllPlans');
Route::get('get-basic-plan/{stripeplanid}', 'Api\User\StripeController@getBasicPlan');
Route::get('get-tier-plan/{stripeplanid}', 'Api\User\StripeController@getTierPlan');
Route::post('validate-coupon', 'Api\User\StripeController@validateCoupon');
Route::delete('cancel-subscription/{id}', 'Api\User\StripeController@destroy')->middleware(['jwt.verify']);
Route::post('subscription-payment', 'Api\User\StripeController@afterLoginSubscriptionPayment')->middleware(['jwt.verify']);

/** Angular Frontend APIs code ends here */

/** Gracenote API data into db cron code start here */
Route::get('gracenote-sources-cron', 'GracenoteCron\GracenoteCronController@gracenoteSourcesCron');
Route::get('gracenote-schedule-program-cron', 'GracenoteCron\GracenoteCronController@gracenoteScheduleProgramCron2');

/** Gracenote API data into db cron code end here */

// Authentication Routes
Route::group(['prefix' => 'auth'], function ($router) {

    /** Angular Frontend APIs code start here */
    Route::post('getzipcodes', 'Auth\AuthController@getZipcodes');
    Route::post('checkemail', 'Auth\AuthController@checkEmail');
    Route::get('devicedetail', 'Auth\AuthController@deviceDetail');
    Route::post('zipcodeInquery', 'Auth\AuthController@failedzipinquery');
    Route::post('send-otp', 'Auth\AuthController@sendOtp');
    Route::post('verify-otp', 'Auth\AuthController@verifyOtp');
    Route::post('account-edit', 'Auth\UserController@accountEdit')->middleware(['jwt.verify']);
    Route::post('multi-session-destroy', 'Auth\UserController@multiSessionDestroy')->middleware(['jwt.verify']);
    Route::post('register', 'Api\User\StripeController@register');
    Route::post('force-change-password', 'Auth\UserController@forceChangePassword')->middleware(['jwt.verify']);
    Route::get('setting-details', 'Api\User\StripeController@showUserDetails')->middleware(['jwt.verify']);

    Route::post('channelListWithGracenote', 'Api\User\NewGracenoteController@channelListWithGracenote')->middleware(['jwt.verify']);
    Route::post('filterChannels/{genre}', 'Api\User\NewGracenoteController@filterChannels')->middleware(['jwt.verify']);
    Route::get('epgguide', 'Api\User\NewGracenoteController@epg_guide')->middleware(['jwt.verify']);
    Route::get('fetchGracenoteData', 'Api\User\NewGracenoteController@fetchGracenoteData');

    Route::post('contactForm', 'Api\User\FooterApiController@contactForm')->middleware(['jwt.verify']);

    Route::post('channel-list', 'Api\User\GracenoteChannelApiController@channelList')->middleware(['jwt.verify']);
    Route::post('recent-channels', 'Api\NewChannelApiController@recentChannels')->middleware(['jwt.verify']);
    Route::post('favourite-channels', 'Api\NewChannelApiController@favouriteChannels')->middleware(['jwt.verify']);
    Route::post('epg-guide', 'Api\NewChannelApiController@epgGuide')->middleware(['jwt.verify']);
    Route::post('new-search', 'Api\NewChannelApiController@searchChannelProgram')->middleware(['jwt.verify']);
    Route::post('all-channel-list', 'Api\User\GracenoteChannelApiController@allChannelList')->middleware(['jwt.verify']);
    Route::get('all-genre', 'Api\NewChannelApiController@allGenre')->middleware(['jwt.verify']);
    Route::post('program-details', 'Api\NewChannelApiController@programDetails')->middleware(['jwt.verify']);
    Route::post('player-details', 'Api\NewChannelApiController@playerDetails')->middleware(['jwt.verify']);

    /** Angular Frontend APIs code ends here */

    Route::post('login', 'Auth\AuthController@login');
    Route::post('user/delete', 'APIController@userdeletenew');
    Route::post('hoteladminlogin', 'Auth\AuthController@hoteladminlogin');
    Route::post('loginCheck', 'Auth\AuthController@loginCheck');
    Route::post('sendNotification', 'NotificationController@sendNotificationApp');
    Route::post('authtokenrefresh', 'Auth\AuthController@authRefesh');
    Route::post('login-with-passcode', 'Auth\AuthController@loginWithPasscode');
    Route::post('change-password', 'Auth\AuthController@changePassword');
    Route::get('check-session', 'Auth\AuthController@checkSession');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('same-device-id-logout', 'Auth\AuthController@samedevicelogout')->name('samedeviceidlogout');
    Route::post('logout-device-session', 'Auth\AuthController@logoutDevice')->name('logoutdeveicesession');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::get('me', 'Auth\AuthController@me');
    Route::get('channelgenre', 'APIController@genrename');
    Route::get('profile', 'Auth\AuthController@profile');
    Route::get('tvprofile', 'Auth\AuthController@tvprofile');
    Route::put('updateprofile', 'Auth\AuthController@updateprofile');
    //zipcodes
    Route::get('zipcode', 'Admin\ZipcodeController@zipjson');
    //channel api

    Route::get('channellist', 'Admin\ChannelAPIController@channeljson');
    Route::get('check', 'Admin\ChannelAPIController@checkdevice');
    Route::get('sort', 'Admin\ChannelAPIController@sortdevice');
    Route::post('channelschdeule', 'Admin\ChannelAPIController@channelschedulejson');
    Route::get('channeldayschdeule', 'Admin\ChannelAPIController@channelDaysSchedule');
    Route::get('channeldayschdeulepaginate', 'Admin\ChannelAPIController@channelDaysSchedulePaginate')->middleware('subscribed');
    Route::get('channeldayschdeuletz', 'Admin\ChannelAPIController@channelDaysScheduletz')->middleware('subscribed');
    Route::get('channeldayschdeuletzwithoffset', 'Admin\ChannelAPIController@channelDaysScheduleoffsettz')->middleware('subscribed');
    Route::get('channeldayschdeuletzwithoffsetnew', 'Admin\ChannelAPIController@channelDaysScheduleoffsettznew')->middleware('subscribed');
    Route::get('channeldayschdeuletztestwithoffset', 'Admin\ChannelAPIController@channelDaysScheduleoffsettz2')->middleware('subscribed');
    Route::get('channeldayschdeulepaginateoffsettz', 'Admin\ChannelAPIController@channelDaysSchedulePaginateoffsettz')->middleware('subscribed');
    Route::get('oldchanneldayschdeule', 'Admin\ChannelAPIController@oldchannelDaysSchedule');
    Route::get('frontendepg', 'Admin\ChannelAPIController@frontEpgchannelDaysSchedule');
    Route::get('frontendepg2', 'Admin\ChannelAPIController@frontEpgchannelDaysSchedule2');
    Route::get('frontendepgpacific', 'Admin\ChannelAPIController@PacificFrontEpgchannelDaysSchedule');
    Route::get('timezonefrontendepg', 'Admin\ChannelController@timezoneEpgchannelDaysSchedule');
    Route::post('search', 'Admin\ChannelController@searchapi');
    Route::get('connecteddevice', 'APIController@connecteddevice');
    Route::match(['put', 'patch', 'post'], 'personaldevicename/{id}', 'Pages\Account\Setting@updatePersonalDeviceName')->middleware(['jwt.verify']);
    Route::get('support', 'APIController@support');
    Route::get('/homescreenbanner', 'HomescreenBannerController@index'); //piyush banner
    Route::get('privacypol', 'APIController@privacypol');
    Route::get('termofuse', 'APIController@termofuse');
    Route::post('addtofavorite/', 'APIController@favoriteChannel');
    Route::post('unfavorite/', 'APIController@unFavoriteChannel');
    Route::get('my_favorites', 'APIController@myFavorites');
    Route::get('my_recents', 'APIController@myRecents');
    Route::post('addtorecent', 'APIController@addToRecent');
    Route::get('searchall', 'APIController@searchOnPlatform');
    Route::get('getdrmkey', 'Admin\BuyDRMController@index');
    Route::post('outsidelocationplay', 'Admin\OutsideLocationPlayController@OLPapi')->middleware(['jwt.verify']);
    Route::post('outsideZipcode', 'Admin\OutsideLocationPlayController@OZ_api')->middleware(['jwt.verify']);

    Route::post('/ga', 'GoogleAnalyticsController@index3')->middleware(['jwt.verify', 'subscribed']);
    Route::post('/ga/duration', 'GoogleAnalyticsController@duration')->middleware(['jwt.verify', 'subscribed']);
    Route::post('/ga/playererror', 'GoogleAnalyticsController@playererror')->middleware(['jwt.verify']);


    Route::post('/sync-session-with-room', 'Admin\HotelController@syncSessionWithHotelRoom')->middleware(['jwt.verify']);
    Route::post('/get-hotelrooms', 'Admin\HotelController@getHotelRooms')->middleware(['jwt.verify']);

    /** Channel SLider API */
    Route::get('channel-sliders', 'Api\ChannelSliderApiController@getChannelSliders')->middleware(['jwt.verify']);
    Route::get('epg-banner', 'Api\ChannelSliderApiController@getEpgBanner')->middleware(['jwt.verify']);
});

// Superadmin Routes
Route::group(['middleware' => ['jwt.verify', 'role:superadmin|admin'], 'prefix' => 'sadmin', 'namespace' => 'Admin'], function ($router) {

    // User Routes
    Route::post('user', 'UserAndRoleController@createUser');
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

Route::post('/loginguide', 'LoginGuideController@index');
Route::post('generic', 'Admin\BuyDRMController@proxyServer');
Route::get('/qrcode', 'APIController@qrcodeapi');
Route::post('/ga2', 'GoogleAnalyticsController@index3');

Route::get('/category', 'EpgController@category');

// Route::post('/qrauth', 'Admin\DashboardController@qrauth');
Route::post('/qrauth', 'WebSocketController@qrauth');
// Route::post('/login/create/qrcode', 'QRLoginController@CreateQrcodeAction');
Route::post('/login/create/qrcode', 'Admin\QRLoginTwoController@CreateQrcodeAction'); //creates QR and save it as png
Route::post('/login/mobile/scan/qrcode', 'Admin\QRLoginTwoController@mobileScanQrcodeAction')->middleware(['jwt.verify']); //gets scaned by phone with key passed in header
Route::get('/login/mobile/scan/qrcode', 'Admin\QRLoginTwoController@redirectToStreamlyTV'); //gets scaned by phone with key passed in header

Route::post('/login/qrcodedoLogin', 'Admin\QRLoginTwoController@qrcodeDoLoginAction'); //this url is used when qr code is scanned successfully
Route::post('/login/scan/qrcode', 'Admin\QRLoginTwoController@isScanQrcodeAction'); //Check whether the code has been scanned
Route::post('/login/entry/login', 'Auth\AuthController@loginEntry');//Check whether the login has been confirmed ,and return the token in response

// Route::fallback(function(){
//     return response()->json([
//         'response_code'=>404,
//         'message' => 'Page Not Found or Wrong method used in postman. If error persists, contact info@website.com'], 404);
// });
