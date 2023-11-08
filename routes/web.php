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
use App\Http\Controllers\BotManController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::view('/botman/chat', 'frontend.botman');
//Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman', 'MainConversationController@handle');
Route::post('/botman', 'MainConversationController@handle');

Route::get('/', function () {
    return view('auth/login');
});
Route::post('/password/login', function () {
    return redirect('/livetv');
});

Route::post('/store-token', [NotificationController::class, 'updateDeviceToken'])->name('store.token');
Route::get('push-notification', [NotificationController::class, 'index']);
Route::post('sendNotification', [NotificationController::class, 'sendNotification'])->name('send.notification');

Auth::routes();
/*add Naresh 15-11-2022*/
Route::get('/cookie/set','HomeController@setCookie');
/*End Naresh 15-11-2022*/

Route::get('/admin', 'Admin\AdminController@index');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/homecache', 'HomeController@indexCache');
Route::get('/checkzip', 'HomeTwoController@checkzipView');
Route::get('/tags', function() {
    return view('frontend.mydata2');
});
Route::get('/tags/find', 'HomeTwoController@find');

Route::get('search', 'HomeTwoController@search')->name('search');
Route::get('mydata', 'HomeTwoController@mydata')->name('mydata');//infinite scroll
Route::get('search2', 'HomeTwoController@search2')->name('search2');
Route::get('dataforselect2', 'HomeTwoController@getdataforselect22')->name('dataforselect2');
Route::get('epgdata', 'HomeTwoController@getepg')->name('epgdata');
Route::get('dataforselectuser', 'HomeTwoController@getdataforselectUser')->name('dataforselectuser');
Route::get('autocomplete', 'HomeTwoController@autocomplete')->name('autocomplete');



Route::get('/subscriptions/allcust','Admin\SubscriptionController@showAllcustomer');
Route::get('/subscriptions/syncuser','Admin\SubscriptionController@syncStripeUser');



Route::middleware(['auth','role:superadmin|admin'])->prefix('admin')->name('admin')->group(function(){
    
    
    Route::post('insertsubscriptionpack','Admin\SubscriptionPackController@store')->name('insertsubscriptionpack');
    
    //subscription 
    Route::resource('subscriptions', 'Admin\SubscriptionController');
     Route::get('/subscriptionsstripe','Admin\SubscriptionController@subscriptionstripe')->name('ss');
     Route::get('/pg','Admin\SubscriptionController@paginatewithscroll');
     Route::get('/subscriptions/view/{id}','Admin\SubscriptionController@show');
   Route::get('/subscriptions/edit/{id}','Admin\SubscriptionController@edit');
    Route::get('changeStatus', 'Admin\SubscriptionController@changeStatus');
    Route::post('insertquotation','Admin\SubscriptionController@store')->name('insertquotation');
    Route::put('subscriptionupdate/{id}','Admin\SubscriptionController@update')->name('subscriptionupdate');
    Route::delete('subscriptiondestroy/{id}','Admin\SubscriptionController@destroy')->name('subscriptiondestroy');

    Route::get('/subscriptions/allcust','Admin\SubscriptionController@showAllcustomer');
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
    Route::get('/hotelrooms', 'Admin\HotelController@hotelrooms');
    Route::get('/hotelroomsess', 'Admin\HotelController@activeSession');
    Route::get('/hotelroomsess/{id}', 'Admin\HotelController@connectedhotel');
    
    Route::resource('/hotelcontracts', 'Admin\HotelContractsController');

    Route::get('hoteladmin/data', 'Admin\HotelController@HotelAdminUserAjaxData');
    Route::get('user/data', 'Admin\UsersController@UserAjaxData');
    Route::get('/users/session', 'Admin\UsersController@activeSession');//for user session
    Route::get('/report/usersdetails', 'Admin\ReportController@userdetails');//for user session piyush
    Route::get('/report/channelanalysis', 'Admin\ReportController@channelanalysis');//for user session piyush
    Route::get('/report/subscriptionsreport/{sort}', 'Admin\ReportController@SubscriptionsReports');//for user session piyush
    Route::get('/report/subscriptionsreport/search/from={from}&to={to}/{value}', 'Admin\ReportController@SubscriptionsReportstimeframe');//for user session piyush
    Route::get('/report/transactionsreport', 'Admin\ReportController@TransactionsReports');//for user session piyush
    Route::get('/report/transactionsreport/{order}/{id}/{no}/page_number={number}', 'Admin\ReportController@TransactionspagingReports');//for user session piyush
    Route::get('/report/transactionsreport/viewtransaction/{id}', 'Admin\ReportController@Transactionsview');//for user session piyush
    Route::get('/report/zipcode', 'Admin\ReportController@zipcode');//for user session piyush
    Route::get('/report/demographic', 'Admin\ReportController@graph');//for user session piyush
    Route::get('/slide/view', 'LoginGuideController@slideview');//slider piyush
    Route::get('/banner/view', 'HomescreenBannerController@bannerview');//slider piyush
    Route::get('/slide/create', 'LoginGuideController@slidecreate');//slider piyush
    Route::get('/banner/create', 'HomescreenBannerController@bannercreate');//slider piyush
    Route::post('/slide/add', 'LoginGuideController@slideadd');//slider piyush
    Route::post('/banner/add', 'HomescreenBannerController@banneradd');//slider piyush
    Route::get('/slide/details/{id}', 'LoginGuideController@singleslideview');//slider piyush
    Route::get('/banner/details/{id}', 'HomescreenBannerController@singlebannerview');//slider piyush
    Route::delete('/slide/delete/{id}', 'LoginGuideController@slidedelete');//slider piyush
    Route::delete('/banner/delete/{id}', 'HomescreenBannerController@bannerdelete');//slider piyush
    Route::put('/slide/update/{id}', 'LoginGuideController@slideupdate');//slider piyush
    Route::put('/banner/update/{id}', 'HomescreenBannerController@bannerupdate');//slider piyush
    Route::put('/slide/update/seconds/{value}', 'LoginGuideController@intervalUpdate');//slider piyush
    Route::get('/stripekeys', 'Admin\StripekeysController@index');
    Route::put('/keysupdate/{status}', 'Admin\StripekeysController@keysupdate');
    Route::get('/users/connected/{id}', 'Admin\UsersController@connecteddevice');//connected devices
    Route::get('/users/create', 'Admin\UsersController@create');
    Route::post('insertuser','Admin\UsersController@store')->name('insertuser');
    Route::get('/users/view/{id}', 'Admin\UsersController@show');
Route::get('/users/edit/{id}', 'Admin\UsersController@edit');
Route::put('userupdate/{id}','Admin\UsersController@update')->name('userupdate');
Route::delete('users/destroy/{id}', 'Admin\UsersController@destroy');
Route::delete('users/sessiondestroy/{id}', 'Admin\UsersController@sessiondestroy')->name('users.sessiondestroy');


Route::get('users/updateall', 'Admin\UsersController@UpdateAll');

  //channel
  Route::get('/channelstatus/create', 'Admin\ChannelController@create');//channels Statust
  
Route::get('/channelstatus', 'Admin\ChannelController@index');//channel
Route::get('/channelstatus2', 'Admin\ChannelController@index2');//channel
Route::get('/channelstatus/view/{id}', 'Admin\ChannelController@show');//channel
Route::get('/channelstatus/edit/{id}', 'Admin\ChannelController@edit');//channel
Route::get('channelstatus/channeldata', 'Admin\ChannelController@channelData');//ajax channel data

Route::get('channelstatus/updateall', 'Admin\ChannelController@UpdateAll');
Route::post('insertchannel','Admin\ChannelController@store')->name('insertchannel');
Route::put('channelupdate/{id}','Admin\ChannelController@update')->name('channelupdate');
   
   //Genres
    Route::get('/allgenre', 'Admin\GenreController@index');//genre
    Route::get('/addgenre', 'Admin\GenreController@addgenre');//genre
    Route::post('/creategenre', 'Admin\GenreController@creategenre');//genre
    Route::get('/deletegenre/{id}', 'Admin\GenreController@deletegenre');//genre
    
    // Zipcode Routes
    Route::get('zipcode2', 'Admin\ZipcodeController@index');
    Route::get('zipcode', 'Admin\ZipcodeController@index2');
    Route::get('allowedzipcode', 'Admin\ZipcodeController@allowedZipcodes');
    Route::get('address', 'Admin\ZipcodeController@addressindex');
    Route::get('addressdatatable', 'Admin\ZipcodeController@addressindexDatatables');

    Route::put('addressupdate/{id}','Admin\ZipcodeController@addressupdate')->name('addressupdate');
    Route::get('/zipcode/create', 'Admin\ZipcodeController@create');
    Route::get('/zipcode/view/{id}', 'Admin\ZipcodeController@show');
    Route::get('/zipcode/edit/{id}', 'Admin\ZipcodeController@edit');
    Route::get('/addressupdateedit/{id}', 'Admin\ZipcodeController@addressupdateedit');
    //Route::post('zipcode/update', 'Admin\ZipcodeController@updateRole');
    Route::post('insertzipcode','Admin\ZipcodeController@store')->name('insertzipcode');
    Route::put('zipcodeupdate/{id}','Admin\ZipcodeController@update')->name('zipcodeupdate');
    Route::delete('zipcode/destroy', 'Admin\ZipcodeController@massDestroy')->name('zipcode.massDestroy');
    Route::get('zipcode/category', 'Admin\ZipcodeController@categoryData');
    Route::get('zipcode/category2', 'Admin\ZipcodeController@categoryData2');
    Route::get('zipcode/updateall', 'Admin\ZipcodeController@UpdateAll');
    
    Route::get('zipcode/allowedzipdata', 'Admin\ZipcodeController@allowedZipdata');
    Route::get('zipcode/map', 'Admin\ZipcodeController@map');
Route::get('zipcode/checkfenceweb', 'Admin\ZipcodeController@webfenceturf');

Route::get('channels/gracenote', 'Admin\GracenoteController@gracenoteData');
    Route::delete('zipcode/{id}/delete', 'Admin\ZipcodeController@deleteRole');
    
 //hls anaylzer

Route::get('/hlsmonitor', 'VideoAnylyzerController@test2');
Route::get('/frontendtext/create', 'Admin\FrontendTextController@create');
Route::get('/frontendtext', 'Admin\FrontendTextController@index');//frontendtext
Route::post('insertfrontendtext','Admin\FrontendTextController@store')->name('insertfrontendtext');
Route::put('fronttextupdate','Admin\FrontendTextController@update')->name('fronttextupdate');
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
Route::get('/report/zipcodeusers/{zipcode}', 'Admin\ReportController@zipcodeUsers');//all users from zipcode
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

});

/*Prev Next channel*/
Route::get('nextprev/{id}', 'LivetvController@index');
/*Prev Next channel*/

Route::get('add-to-log', 'HomeTwoController@myTestAddToLog');

Route::get('logActivity', 'HomeTwoController@logActivity');




Route::get('/logout', 'HomeTwoController@logout')->name('logout');
Route::get('/login/google', 'HomeTwoController@rediretToGoogleLogin')->name('google.login');

Route::get('/google/login', 'HomeTwoController@handleGoogleCallback');
Route::post('/user/register', 'HomeTwoController@createUser');
Route::post('/user/contactformsend', 'HomeTwoController@contactformsend');
Route::post('/user/checkzipafterlogin', 'HomeTwoController@checkzipAfterGoogleLogin');

Route::post('/forgotpassword', 'Auth\ForgotPasswordController@webgetResetToken');
Route::get('/invoice', 'Pages\Account\Setting@invoice');
Route::get('user/invoice/{invoice}','Pages\Account\Setting@invoicePDF' );
Route::get('/payment/{stripeplanid}', 'Pages\Payment@showPage')->name('payment');
Route::get('/tierpayment/{stripeplanid}', 'Pages\Payment@showTierPage')->name('tierpayment');
Route::get('/subscription', 'HomeController@SubscriptionPage');
Route::get('/planexpire', 'HomeController@PlanRenews');
Route::get('/selectplan', 'Pages\Payment@showPlan');
Route::get('/livetv', 'Pages\LiveTv@showPage');
Route::get('/currentepisode', 'Pages\LiveTv@getcurrentepisode');
Route::get('/livetv/list', 'Pages\LiveTv@showAllPage');
Route::get('/livetv/recent', 'Pages\LiveTv@showRecent');
Route::get('/livetv2/{channel}/{genre}', 'Pages\LiveTv@showChannel');
Route::get('/livetv/{channel}/{genre}', 'Pages\LiveTv@showChannel2')->middleware(['auth','subscribed']);
Route::get('/livetv/{genre}', 'Pages\LiveTv@genrePage');
Route::get('/videojs', 'Pages\LiveTv@videojs');
Route::get('/videojs2', 'Pages\LiveTv@videojs2');
Route::get('/faq', 'Pages\Faq@showPage');
Route::get('/download', 'Pages\Faq@downloadPage');
Route::get('/termsofuse', 'Pages\Faq@termsofuse');
Route::get('/privacy', 'Pages\Faq@privacy');
Route::get('/accessibility', 'Pages\Faq@accessibility');

Route::get('/account/settings3', 'Pages\Account\Setting@showSettingpage');
Route::get('/account/settings2', 'Pages\Account\Setting@showSettingpage2');
Route::get('/account/settings', 'Pages\Account\Setting@showSettingpage3');
Route::get('/account/devices', 'Pages\Account\Setting@showSettingpage4');
Route::get('/account/refresh', 'Pages\Account\Setting@userAskedtoRefreshToken');
Route::get('/account/settings/bug', 'Pages\Account\Setting@BugReportView');
Route::post('/sendbugreport', 'Pages\Account\Setting@sendBugReport')->name('sendbugreport');
Route::get('/verified', 'HomeController@verified');
// Route::get('/checkAvailabilty', 'HomeController@checkAvailabilty');
Route::get('/checkAvailabilty', 'HomeController@checkAvailabilty2');
Route::post('/checkAvailabiltyStore', 'Admin\DeclineZipsInquiryController@failedzipinquery');
Route::post('/getzipcodes', 'Admin\DeclineZipsInquiryController@getZipcodes');
Route::get('/contactform', 'HomeController@contactform');
Route::get('/devices', 'HomeController@showSupportedDevices');
Route::get('/helpandsupport', 'HomeController@helpandsupp');
Route::delete('user/sessiondestroy/{id}', 'Pages\Account\Setting@sessiondestroy')->name('user.sessiondestroy');
Route::put('user/personaldevicename/{id}', 'Pages\Account\Setting@updatePersonalDeviceName')->name('user.personaldevicename');
Route::delete('user/subscriptiondestroy/{id}','Pages\Account\Setting@destroy')->name('user.subscriptiondestroy');
Route::put('/userupdatezip/{id}','Pages\Account\Setting@updateZipCode')->name('userupdatezip');
Route::put('userupdate/{id}','Pages\Account\Setting@update')->name('userupdate');
Route::post('/insertpay/{StripeplanId}','Pages\Payment@pay')->name('insertpay');//send form data here
Route::post('favorite/', 'Pages\LiveTv@favoriteChannel');
Route::post('unfavorite/', 'Pages\LiveTv@unFavoriteChannel');
Route::get('my_favorites', 'Pages\LiveTv@myFavorites');

Route::get('api/vuechannellist', 'EpgController@channellist');
Route::get('api/vueprogramlistwithid', 'EpgController@programelistwithid');
Route::get('api/auth/tizenepg', 'TizenEPGController@channelDaysSchedule')->middleware(['jwt.verify','subscribed']);
Route::get('api/auth/v2/tizenepg', 'TizenEPGController@channelDaysScheduleV2')->middleware(['jwt.verify','subscribed']);
Route::get('api/auth/v2/tizenepgwithoffset', 'TizenEPGController@channelDaysScheduleV2offset')->middleware(['jwt.verify','subscribed']);
Route::get('api/auth/v2/tizenEPGschedule', 'TizenEPGController@channelDaysnew')->middleware(['jwt.verify','subscribed']);
Route::get('api/tizenepg/search', 'TizenEPGController@channelSearchSchedule');
Route::get('api/vuefavoritelist', 'EpgController@favoritelist');
Route::get('api/vueprogramlist', 'EpgController@programelist');
Route::get('api/vuesinglechanneldetails', 'EpgController@singlechanneldetails');
Route::post('api/vuefavorite', 'EpgController@favoriteChannel');
Route::post('api/vueunfavorite', 'EpgController@unFavoriteChannel');


Route::get('/ffmpeg', 'VideoAnylyzerController@index');
Route::get('/ffmpeg2', 'VideoAnylyzerController@probeAudioFile');
Route::get('/ffmpeg3', 'VideoAnylyzerController@testFprobe');
Route::get('/ffmpeg4', 'VideoAnylyzerController@testProbeOnRemoteFile');
Route::get('/epgviewr', 'Pages\LiveTv@epgScreen');
Route::get('/epgviewr2', 'Pages\LiveTv@epgScreen2');


Route::get('/drm', 'Admin\BuyDRMController@WidevinePlayer');
Route::get('/drm2', 'Admin\BuyDRMController@WidevinePlayer2');
Route::get('/ism', 'Admin\BuyDRMController@WidevinePlayer3');
Route::get('/ism2', 'Admin\BuyDRMController@WidevinePlayer4');
Route::get('/fair', 'Admin\BuyDRMController@fairPlayer');
Route::get('/fairnotoken', 'Admin\BuyDRMController@fairPlayernotoken');
Route::get('/fair2', 'Admin\BuyDRMController@fairPlayer2');
Route::get('/testing', 'Admin\DashboardController@testing');
Route::get('/qrtesting', 'Admin\DashboardController@qrtesting');
Route::get('/qrtesting2', 'Admin\DashboardController@qr')->name('qrtesting2');;
Route::get('/qrscanner', 'Admin\DashboardController@qrscanner');
Route::get('/qrscanner2', 'Admin\DashboardController@qrscanner2');

Route::get('/react', 'Pages\LiveTv@webReact');
Route::get('/vue', 'EpgController@staticepg');
Route::get('/vuehtml', 'EpgController@vuehtml');
Route::get('ajax-check', 'Admin\SubscriptionController@ajaxCheck')->name('ajax-check');//check coupon

Route::get('address-check', 'HomeController@addressCheck')->name('address-check');

Route::view('/search4', 'frontend.search4');
Route::get('/debug', 'Admin\DashboardController@debugmpd');
Route::get('/upcomingevent/{callsign}/{EventTitle}', 'SearchController@upcomingEvent');

// Route::get('/user/find', 'SearchController@searchevents');
Route::get('/user/find', 'SearchController@searchUsers');
Route::resource('bugreportings','Admin\BugReportingController');
Route::get('/dropzone1','RenameController@renameDisplay');
Route::post('dropzone1/store','RenameController@uploadImages')->name('dropzone.store');


Route::post('dropzone1/delete', 'RenameController@deleteImage')->name('dropzone.delete');

Route::get('dropzone', 'DropzoneController@index');

Route::post('dropzone/upload', 'DropzoneController@upload')->name('dropzone.upload');

Route::get('dropzone/fetch', 'DropzoneController@fetch')->name('dropzone.fetch');

Route::get('dropzone/delete', 'DropzoneController@delete')->name('dropzone.delete');

Route::get('/stripesync', function (Request $request) {
    Artisan::call('stripe:sync-renewals');
    
    return redirect('admin/subscriptions')->with('info',Artisan::output());
    //return Artisan::output();
 });
//  Route::get('/botdemo/chatserver1', 'HomeController@index')->name('home');

Route::post('admin/multisessiondestroy', 'Admin\UsersController@multisessiondestroy')->name('multisessiondestroy')->middleware(['auth']);
Route::post('web/login/entry/login', 'Admin\QRLoginTwoController@loginEntry');//Check whether the login has been confirmed ,and return the token in response
Route::post('web/loginws', 'Admin\QRLoginTwoController@loginWS');//Check when passcode hasbeen received
Route::delete('cancelsubscription/{id}','SubscriptionController@subscriptiondestroy');

Route::post('/stripe/webhook', 'StripeController@index');

Route::middleware(['auth','role:superadmin|admin|salesuser'])->prefix('salesuser')->name('salesuser')->group(function(){
    Route::get('/paymentlink', 'PaymentlinkController@index');
    Route::post('/payment_links', 'PaymentlinkController@paymentlinks');
    Route::get('/add_masteruser', 'PaymentlinkController@addmasteruser');
    Route::post('/create_master_user', 'PaymentlinkController@createmasteruser');
    Route::get('/masteruserlist', 'PaymentlinkController@masteruserlist');
    Route::get('/paymentlist', 'PaymentlinkController@paymentlist');
    Route::get('/resendVerificationEmail/{email}', 'PaymentlinkController@resendVerificationEmail');
    Route::get('/masteruserpagination', 'PaymentlinkController@masteruserpagination');
    Route::get('/paymentlistpagination', 'PaymentlinkController@paymentlistpagination');
});

Route::middleware(['auth','role:superadmin|admin'])->group(function(){
 //iptv
 Route::get('/managed-iptv', 'iptvController@index');
 Route::post('/managed-iptv/submit_info', 'iptvController@submitInfo');
});

Route::get('/checkout/{master_id}', 'PaymentlinkController@checkout');
Route::post('checkout/backendpayment', 'PaymentlinkController@backendpayment');
Route::get('/success', 'PaymentlinkController@paymentsuccess');
Route::get('/fail', 'PaymentlinkController@paymentfail');