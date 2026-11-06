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

use App\Http\Controllers\Admin\AdminGracenoteController;
use App\Http\Controllers\Admin\AdminServerLogController;
use App\Http\Controllers\BotManController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AngularController;
use App\Http\Controllers\SubscriptionCronController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Api\ApiHoaController;
use App\Http\Controllers\GracenoteCron\GracenoteCronController;
use App\Models\Subscription;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


Route::middleware(['auth', 'role:superadmin|admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::post('insertsubscriptionpack', 'Admin\SubscriptionPackController@store')->name('insertsubscriptionpack');

    //subscription
    Route::resource('subscriptions', 'Admin\SubscriptionController');
    Route::get('subscriptions-new', 'Admin\SubscriptionController@index2');
    Route::get('custom-subscriptions', 'Admin\SubscriptionController@index3');
    Route::get('subscriptions/custom/user/{id}', 'Admin\SubscriptionController@customUserView')->name('subscriptions.custom.view');
    Route::get('subscriptions/user/{id}/mail/upgrade', 'Admin\SubscriptionController@subscriptionUpgradeMail')->name('subscriptions.user.mail.upgrade');
    Route::get('subscriptions/user/{id}/cancel', 'Admin\SubscriptionController@subscriptionCancelById')->name('subscriptions.user.cancel');
    Route::post('subscriptions/users/bulk/action', 'Admin\SubscriptionController@bulkAction')->name('subscriptions.users.bulk.action');
    Route::get('subscriptions/users/export/{type}', 'Admin\SubscriptionController@exportUsersSubscription')->name('subscriptions.users.export');
    Route::get('custom-subscriptions/export', 'Admin\SubscriptionController@exportCustomSubscription')->name('custom.subscriptions.export');
    Route::get('subscriptions/plan-request/reject/{id}', 'Admin\SubscriptionController@planRequestReject')->name('subscriptions.plan.request.reject');
    Route::get('subscriptions/plan-request/accept/{id}', 'Admin\SubscriptionController@planRequestAccept')->name('subscriptions.plan.request.accept');
    Route::get('custom-subscriptions/cancel/{id}', 'Admin\SubscriptionController@customSubscriptionCancel')->name('custom.subscriptions.cancel');
    Route::get('stripe/sync/{id}', 'Admin\SubscriptionController@stripeSyncById')->name('stripe.sync');

    Route::post('subscriptions/custom/user/store', 'Admin\SubscriptionController@storeUserAndSubscription')->name('subscriptions.custom.store');
    Route::get('/subscriptionsstripe', 'Admin\SubscriptionController@subscriptionstripe')->name('ss');
    Route::get('/pg', 'Admin\SubscriptionController@paginatewithscroll');
    Route::get('/subscriptions/view/{id}', 'Admin\SubscriptionController@show');
    Route::get('/subscriptions/edit/{id}', 'Admin\SubscriptionController@edit');
    Route::get('changeStatus', 'Admin\SubscriptionController@changeStatus');
    Route::post('insertquotation', 'Admin\SubscriptionController@store')->name('insertquotation');
    Route::put('subscriptionupdate/{id}', 'Admin\SubscriptionController@update')->name('subscriptionupdate');
    Route::delete('subscriptiondestroy/{id}', 'Admin\SubscriptionController@destroy')->name('subscriptiondestroy');

    Route::get('/subscriptions/allcust', 'Admin\SubscriptionController@showAllcustomer');
    //gracenote
    Route::get('/channels', 'Admin\GracenoteController@index'); //gracenote
    Route::get('/channels/create', 'Admin\GracenoteController@create'); //gracenote
    Route::get('/channels/view/{id}', 'Admin\GracenoteController@show'); //gracenote
    Route::get('/channels/edit/{id}', 'Admin\GracenoteController@edit'); //gracenote
    Route::put('channelupdate/{id}', 'Admin\GracenoteController@update')->name('channelupdate');
    //subscription pack
    Route::get('/subscriptionpack', 'Admin\SubscriptionPackController@index');
    Route::get('/subscriptionpack/view/{id}', 'Admin\SubscriptionPackController@show');
    Route::get('/subscriptionpack/edit/{id}', 'Admin\SubscriptionPackController@edit');
    Route::get('/subscriptionpack/create', 'Admin\SubscriptionPackController@create');
    Route::put('subscriptionpackupdate/{id}', 'Admin\SubscriptionPackController@update')->name('subscriptionpackupdate');
    Route::delete('subscriptionpack/destroy/{id}', 'Admin\SubscriptionPackController@destroy');

    Route::get('/subscriptionpack/plans', 'Admin\SubscriptionPackController@viewPlan');
    // User routes
    Route::get('/users', 'Admin\UsersController@index')->name('users');
    Route::get('/users/new', 'Admin\UsersController@index2')->name('users.new.index');
    Route::get('/users/status/{id}', 'Admin\UsersController@changeStatus')->name('users.status');
    Route::get('/users/delete/{id}', 'Admin\UsersController@destroyById')->name('users.delete');
    Route::post('/users/bulk/action', 'Admin\UsersController@bulkAction')->name('users.bulk.action');
    // Route::get('/fetchzipcode', 'Admin\UsersController@getZip');
    Route::get('users/remove/device-name/{id}', 'Admin\UsersController@removeDeviceName')->name('users.remove.devicename');



    Route::get('/hotelrooms', 'Admin\HotelController@hotelrooms');
    Route::get('/hotelroomsess', 'Admin\HotelController@activeSession')->name('hotel.room');
    Route::get('/hotel/user/room/export/{type}', 'Admin\HotelController@exportUserRoom')->name('hotel.user.room.export');
    Route::get('/hotelroomsess/{id}', 'Admin\HotelController@connectedhotel');

    Route::resource('/hotelcontracts', 'Admin\HotelContractsController');

    Route::get('hoteladmin/data', 'Admin\HotelController@HotelAdminUserAjaxData');
    Route::get('user/data', 'Admin\UsersController@UserAjaxData');
    Route::get('users/export/{type}', 'Admin\UsersController@exportUser')->name('users.export');
    Route::get('users/session/export/{type}', 'Admin\UsersController@exportUserSession')->name('users.session.export');
    Route::get('users/session/{id}/export/{type}', 'Admin\UsersController@exportUserSessionDevice')->name('users.session.device.export');
    Route::get('/users/session', 'Admin\UsersController@activeSession')->name('user.session'); //for user session
    Route::get('/users/custom', 'Admin\UsersController@customUserList')->name('users.custom.list');
    Route::get('/users/custom/export/{type}', 'Admin\UsersController@exportCustomUser')->name('users.custom.export');
    Route::get('/report/usersdetails', 'Admin\ReportController@userdetails'); //for user session piyush
    Route::get('/report/channelanalysis', 'Admin\ReportController@channelanalysis'); //for user session piyush
    Route::get('/report/subscriptionsreport', 'Admin\ReportController@SubscriptionsReports'); //for user session piyush
    Route::get('/report/subscription/export/{type}', 'Admin\ReportController@exportSubscription')->name('report.subscription.export'); //for user session piyush
    Route::get('/report/subscriptionsreport/search/from={from}&to={to}/{value}', 'Admin\ReportController@SubscriptionsReportstimeframe'); //for user session piyush
    Route::get('/report/transactionsreport', 'Admin\ReportController@TransactionsReports'); //for user session piyush
    Route::get('/report/transactionsreport/{order}/{id}/{no}/page_number={number}', 'Admin\ReportController@TransactionspagingReports'); //for user session piyush
    Route::get('/report/transactionsreport/viewtransaction/{id}', 'Admin\ReportController@Transactionsview'); //for user session piyush
    Route::get('/report/zipcode', 'Admin\ReportController@zipcode'); //for user session piyush
    Route::get('/report/demographic', 'Admin\ReportController@graph'); //for user session piyush
    Route::get('/report/subscription/detail/{id}', 'Admin\ReportController@subscriptionDetailView')->name('report.subscription.detail');
    Route::get('/report/subscription/invoices/{id}', 'Admin\ReportController@invoiceListByUser')->name('report.subscription.invoices');
    Route::get('/slide/view', 'LoginGuideController@slideview'); //slider piyush
    Route::get('/banner/view', 'HomescreenBannerController@bannerview'); //slider piyush
    Route::get('/slide/create', 'LoginGuideController@slidecreate'); //slider piyush
    Route::get('/banner/create', 'HomescreenBannerController@bannercreate'); //slider piyush
    Route::post('/slide/add', 'LoginGuideController@slideadd'); //slider piyush
    Route::post('/banner/add', 'HomescreenBannerController@banneradd'); //slider piyush
    Route::get('/slide/details/{id}', 'LoginGuideController@singleslideview'); //slider piyush
    Route::get('/banner/details/{id}', 'HomescreenBannerController@singlebannerview'); //slider piyush
    Route::delete('/slide/delete/{id}', 'LoginGuideController@slidedelete'); //slider piyush
    Route::delete('/banner/delete/{id}', 'HomescreenBannerController@bannerdelete'); //slider piyush
    Route::put('/slide/update/{id}', 'LoginGuideController@slideupdate'); //slider piyush
    Route::put('/banner/update/{id}', 'HomescreenBannerController@bannerupdate'); //slider piyush
    Route::put('/slide/update/seconds/{value}', 'LoginGuideController@intervalUpdate'); //slider piyush

    // channel slider created by Annu
    Route::get('/channel-slider-create', 'Admin\ChannelSliderController@channelSlideCreate');
    Route::post('/channel-slider-store', 'Admin\ChannelSliderController@storeChannelSlide');
    Route::get('/all-channel-sliders', 'Admin\ChannelSliderController@allChannelSliders');
    Route::delete('/channel-slider-delete/{id}', 'Admin\ChannelSliderController@channelSlideDelete');
    Route::get('/channel-slider-edit/{id}', 'Admin\ChannelSliderController@channelSlideEdit');
    Route::put('/channel-slider-edit/{id}', 'Admin\ChannelSliderController@channelSlideUpdate');

    Route::get('/stripekeys', 'Admin\StripekeysController@index');
    Route::get('/qr-code/status/', 'Admin\StripekeysController@qrCodeStatus')->name('qr-code.status');
    Route::put('/keysupdate/{status}', 'Admin\StripekeysController@keysupdate');
    Route::get('/users/connected/{id}', 'Admin\UsersController@connecteddevice')->name('users.connected.devices'); //connected devices
    Route::get('/users/connected-new/{id}', 'Admin\UsersController@connectedDeviceNew')->name('users.connected.devices.new'); //connected devices
    Route::get('/users/create', 'Admin\UsersController@create');
    Route::post('insertuser', 'Admin\UsersController@store')->name('insertuser');
    Route::get('/users/view/{id}', 'Admin\UsersController@show')->name('users.view');
    Route::get('/users/edit/{id}', 'Admin\UsersController@edit')->name('users.edit');
    Route::put('userupdate/{id}', 'Admin\UsersController@update')->name('userupdate');
    Route::delete('users/destroy/{id}', 'Admin\UsersController@destroy')->name('users.destroy');
    Route::delete('users/sessiondestroy/{id}', 'Admin\UsersController@sessiondestroy')->name('users.sessiondestroy');
    Route::get('users/session/destroy/{id}', 'Admin\UsersController@destroyUserSessionById')->name('users.session.destroy');


    Route::get('users/updateall', 'Admin\UsersController@UpdateAll');

    // Account Managements
    Route::get('/users/list-by-admin', 'Admin\AdminAccountManagementController@usersListByAdmin')->name('users.list-by-admin');

    // activity
    Route::get('/activities', 'Admin\AdminActivityController@index')->name('activity');
    Route::get('/activities/users/{id}', 'Admin\AdminActivityController@index2')->name('users.activity');
    Route::get('/activities/session/{token?}', 'Admin\AdminActivityController@bySession')->name('users.activity.session.token');
    Route::get('/activities/sub-users/{passcode}', 'Admin\AdminActivityController@subUserActivity')->name('sub-user.activity');
    Route::get('/activities/sub-users/session/{token?}', 'Admin\AdminActivityController@subUserBySession')->name('sub-user.activity.session');

    //channel
    Route::get('/channelstatus/create', 'Admin\ChannelController@create')->name('channel.create'); //channels Statust
    Route::get('/channelstatus', 'Admin\ChannelController@index3');
    // Route::get('/channelstatus', 'Admin\ChannelController@index'); //channel
    Route::get('/channel-new', 'Admin\ChannelController@index3'); //channel
    Route::get('/channelstore', 'Admin\ChannelController@channelStore'); //channel Store

    Route::get('/channelstatus2', 'Admin\ChannelController@index2'); //channel
    Route::get('/channelstatus/view/{id}', 'Admin\ChannelController@show'); //channel
    Route::get('/channelstatus/edit/{id}', 'Admin\ChannelController@edit'); //channel
    Route::get('channelstatus/channeldata', 'Admin\ChannelController@channelData'); //ajax channel data

    Route::get('channelstatus/updateall', 'Admin\ChannelController@UpdateAll');
    Route::post('insertchannel', 'Admin\ChannelController@store')->name('insertchannel');
    Route::put('channelupdate/{id}', 'Admin\ChannelController@update')->name('channelupdate');
    Route::get('channel/status/{id}', 'Admin\ChannelController@changeStatus')->name('channel.status');
    Route::post('channel/bulk/action', 'Admin\ChannelController@bulkAction')->name('channel.bulk.action');
    Route::get('channel/export/{type}', 'Admin\ChannelController@exportChannelReport')->name('channel.report.export');

    //Genres
    Route::get('/allgenre', 'Admin\GenreController@index'); //genre
    Route::get('/allgenre-new', 'Admin\GenreController@index2'); //genre
    Route::get('/addgenre', 'Admin\GenreController@addgenre'); //genre
    Route::post('/creategenre', 'Admin\GenreController@creategenre'); //genre
    Route::get('/deletegenre/{id}', 'Admin\GenreController@deletegenre'); //genre
    Route::get('/genre/status/{id}', 'Admin\GenreController@statusChange')->name('genre.status');
    // Zipcode Routes
    Route::get('zipcode-new', 'Admin\ZipcodeController@index3');
    Route::get('zipcode2', 'Admin\ZipcodeController@index');
    Route::get('zipcode', 'Admin\ZipcodeController@index2');
    Route::get('userzipcoderule', 'Admin\ZipcodeController@userZipcodeRuleShow');
    Route::get('/userzipcoderule/edit/{id}', 'Admin\ZipcodeController@userZipcodeRuleEdit');
    Route::put('/userzipcoderuleupdate/{id}', 'Admin\ZipcodeController@userZipcodeRuleUpdate')->name('userzipcoderuleupdate');
    Route::get('/userzipcoderule/create', 'Admin\ZipcodeController@userZipcodeRuleCreate');
    Route::post('/userzipcoderuleadd', 'Admin\ZipcodeController@userZipcodeRuleAdd')->name('userzipcoderuleadd');
    Route::delete('/userzipcoderule/delete/{id}', 'Admin\ZipcodeController@userZipcodeRuleDelete')->name('userzipcoderuledelete');
    // Localzipcode
    Route::get('local-zipcode', 'Admin\LocalZipcodeController@index');
    Route::get('local-zipcode/{zipcode}', 'Admin\LocalZipcodeController@zipcodeWiseChannel')->name('zipcode.local.channel');
    Route::get('local-zipcode/add/{zipcode}/{channelId}', 'Admin\LocalZipcodeController@addZipcodeToChannel')->name('zipcode.add.local.channel');
    Route::get('local-zipcode/status/{id}', 'Admin\LocalZipcodeController@changeStatus')->name('zipcode.local.channel.status');
    Route::get('local-zipcode/delete/{id}', 'Admin\LocalZipcodeController@delete')->name('zipcode.local.channel.delete');
    Route::get('local-zipcode/disable/{zipcode}', 'Admin\LocalZipcodeController@statusDisableForAllLocalChannel')->name('zipcode.local.channel.disable');
    Route::get('local-zipcode/enable/{zipcode}', 'Admin\LocalZipcodeController@statusEnableForAllLocalChannel')->name('zipcode.local.channel.enable');
    Route::get('local-zipcode/export/data', 'Admin\LocalZipcodeController@export')->name('zipcode.local.export');

    Route::get('allowedzipcode', 'Admin\ZipcodeController@allowedZipcodes');
    Route::get('localzipcode', 'Admin\ZipcodeController@localZipcodes');
    Route::get('address', 'Admin\ZipcodeController@addressindex');
    Route::get('addressdatatable', 'Admin\ZipcodeController@addressindexDatatables');

    Route::put('addressupdate/{id}', 'Admin\ZipcodeController@addressupdate')->name('addressupdate');
    Route::get('/zipcode/create', 'Admin\ZipcodeController@create');
    Route::get('/zipcode/view/{id}', 'Admin\ZipcodeController@show');
    Route::get('/zipcode/edit/{id}', 'Admin\ZipcodeController@edit');
    Route::get('/addressupdateedit/{id}', 'Admin\ZipcodeController@addressupdateedit');
    //Route::post('zipcode/update', 'Admin\ZipcodeController@updateRole');
    Route::post('insertzipcode', 'Admin\ZipcodeController@store')->name('insertzipcode');
    Route::put('zipcodeupdate/{id}', 'Admin\ZipcodeController@update')->name('zipcodeupdate');
    Route::delete('zipcode/destroy', 'Admin\ZipcodeController@massDestroy')->name('zipcode.massDestroy');
    Route::get('zipcode/category', 'Admin\ZipcodeController@categoryData');
    Route::get('zipcode/category2', 'Admin\ZipcodeController@categoryData2');
    Route::get('zipcode/updateall', 'Admin\ZipcodeController@UpdateAll');

    Route::get('zipcode/allowedzipdata', 'Admin\ZipcodeController@allowedZipdata');
    Route::get('zipcode/localzipdata', 'Admin\ZipcodeController@localzipdata');
    Route::post('zipcode/toggle-status', 'Admin\ZipcodeController@toggleZipcodeStatus');

    Route::get('zipcode/map', 'Admin\ZipcodeController@map');
    Route::get('zipcode/checkfenceweb', 'Admin\ZipcodeController@webfenceturf');

    Route::get('channels/gracenote', 'Admin\GracenoteController@gracenoteData');
    Route::delete('zipcode/{id}/delete', 'Admin\ZipcodeController@deleteRole');

    //hls anaylzer

    Route::get('/hlsmonitor', 'VideoAnylyzerController@test2');
    Route::get('/frontendtext/create', 'Admin\FrontendTextController@create');
    Route::get('/frontendtext', 'Admin\FrontendTextController@index'); //frontendtext
    Route::post('insertfrontendtext', 'Admin\FrontendTextController@store')->name('insertfrontendtext');
    Route::put('fronttextupdate', 'Admin\FrontendTextController@update')->name('fronttextupdate');
    // Outside play location

    Route::get('Outsidelocationplay', 'Admin\OutsideLocationPlayController@index');

    Route::get('Outsidelocationplay/data', 'Admin\OutsideLocationPlayController@OLPajaxData');
    Route::get('declinezipinquiries', 'Admin\DeclineZipsInquiryController@index')->name('declinezipinquiries');
    Route::get('declinezipinquiries/data', 'Admin\DeclineZipsInquiryController@ajaxIndex');
    Route::get('bugreport', 'Admin\BugReportingController@index')->name('bugreport');
    Route::get('bugreport/data', 'Admin\BugReportingController@ajaxIndex');

    /*add Naresh 15-11-2022*/
    Route::get('affiliatereport', 'Admin\AffiliateReportController@index')->name('affiliatereport');
    /*End Naresh 15-11-2022*/
    Route::get('/report/zipcodeusers/{zipcode}', 'Admin\ReportController@zipcodeUsers'); //all users from zipcode
    /*Admin Category List Naresh-20-03-2023 */
    Route::get('/chatboat_category', 'Admin\ChatboatCategoryController@index')->name('chatboat_category.index');
    Route::get('/chatboat_category/create', 'Admin\ChatboatCategoryController@create')->name('chatboat_category.create');
    Route::post('/chatboat_category/store', 'Admin\ChatboatCategoryController@store')->name('chatboat_category.store');
    Route::get('/chatboat_category/view/{id}', 'Admin\ChatboatCategoryController@view');
    Route::get('/chatboat_category/edit/{id}', 'Admin\ChatboatCategoryController@edit')->name('chatboat_category.edit');
    Route::post('/chatboat_category/update/{id}', 'Admin\ChatboatCategoryController@update')->name('chatboat_category.update');
    Route::get('/chatboat_category/destroy/{id}', 'Admin\ChatboatCategoryController@destroy')->name('chatboat_category.destroy');


    /*Admin FAQ List Naresh-20-03-2023*/

    Route::get('/faq', 'Admin\FaqChatboatController@index')->name('faq.index');
    Route::get('/faq/create', 'Admin\FaqChatboatController@create')->name('faq.create');
    Route::post('/faq/store', 'Admin\FaqChatboatController@store')->name('faq.store');
    Route::get('/faq/view/{id}', 'Admin\FaqChatboatController@view');
    Route::get('/faq/edit/{id}', 'Admin\FaqChatboatController@edit')->name('faq.edit');
    Route::post('/faq/update/{id}', 'Admin\FaqChatboatController@update')->name('faq.update');
    Route::get('/faq/destroy/{id}', 'Admin\FaqChatboatController@destroy')->name('faq.destroy');

    Route::get('/chat-history', 'Admin\ChatHistoryController@index')->name('chat.history');;

    Route::post('/ajax-sendmail', 'Admin\SendMailController@handleRequest')->name('admin.sendmail');

    Route::post('/ajax-reply-data', 'Admin\SendMailController@AnswerHistory');

    // Hoa Routes
    Route::get('/hoa/sub-users/{id}', 'Admin\AdminHoaController@index')->name('hoa.sub-users');
    Route::get('/hoa/sub-users-session/{passcode}', 'Admin\AdminHoaController@sessionByPasscode')->name('hoa.sub-users-session');
    Route::get('/hoa/sub-users-export/{id}', 'Admin\AdminHoaController@exportIntoExcel')->name('hoa.sub-users-export');
    Route::get('/hoa/sub-users-ordering/{id}', 'Admin\AdminHoaController@houseListOrdering')->name('hoa.sub-users-ordering');

    // logs route for log checking and
    Route::get('/logs', [AdminServerLogController::class, 'index']);
    Route::get('/logs/clear', [AdminServerLogController::class, 'clear']);

    // Gracenote sync
    Route::get('/gracenote/channel-data/{prgSvcId?}', [GracenoteCronController::class, 'programBySinglePrgSvcId'])->name('gracenote.data.pull');
});



