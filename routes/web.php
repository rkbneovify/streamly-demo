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



// qrcode generate
Route::get('qrcode/generate', function (Request $request) {
    $url = $request->get('url', $request->url);

    $qr = QrCode::format('png')
        ->size(300)
        ->generate($url);

    return response($qr)
        ->header('Content-Type', 'image/png');
})->name('qrcode.generate');

Route::view('/botman/chat', 'frontend.botman');
//Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman', 'MainConversationController@handle');
Route::post('/botman', 'MainConversationController@handle');

// Route::get('/', function () {
//     return view('auth/login');
// });
Route::post('/password/login', function () {
    return redirect('/livetv');
});

Route::post('/store-token', [NotificationController::class, 'updateDeviceToken'])->name('store.token');
Route::get('push-notification', [NotificationController::class, 'index']);
Route::post('sendNotification', [NotificationController::class, 'sendNotification'])->name('send.notification');

Auth::routes();
/*add Naresh 15-11-2022*/
Route::get('/cookie/set', 'HomeController@setCookie');
/*End Naresh 15-11-2022*/

Route::get('/admin', 'Admin\AdminController@index');
// Route::get('/', 'HomeController@index')->name('home');
Route::get('/homecache', 'HomeController@indexCache');
Route::get('/checkzip', 'HomeTwoController@checkzipView');
Route::get('/tags', function () {
    return view('frontend.mydata2');
});
Route::get('/tags/find', 'HomeTwoController@find');
Route::post('/api/setzipcode', 'Pages\LiveTv@setzipcode');
Route::get('search', 'HomeTwoController@search')->name('search');
Route::get('mydata', 'HomeTwoController@mydata')->name('mydata'); //infinite scroll
Route::get('search2', 'HomeTwoController@search2')->name('search2');
Route::get('dataforselect2', 'HomeTwoController@getdataforselect22')->name('dataforselect2');
Route::get('epgdata', 'HomeTwoController@getepg')->name('epgdata');
Route::get('dataforselectuser', 'HomeTwoController@getdataforselectUser')->name('dataforselectuser');
Route::get('autocomplete', 'HomeTwoController@autocomplete')->name('autocomplete');



Route::get('/subscriptions/allcust', 'Admin\SubscriptionController@showAllcustomer');
Route::get('/subscriptions/syncuser', 'Admin\SubscriptionController@syncStripeUser');
Route::get('/subscription/success', 'Admin\SubscriptionController@paymentSuccess');
Route::get('/subscription/cancel', 'Admin\SubscriptionController@paymentCancel');



/*Prev Next channel*/
Route::get('nextprev/{id}', 'LivetvController@index');
/*Prev Next channel*/

Route::get('add-to-log', 'HomeTwoController@myTestAddToLog');

Route::get('logActivity', 'HomeTwoController@logActivity');



/*
Route::get('/logout', 'HomeTwoController@logout')->name('logout');
Route::get('/login/google', 'HomeTwoController@rediretToGoogleLogin')->name('google.login');

Route::get('/google/login', 'HomeTwoController@handleGoogleCallback');
Route::post('/user/register', 'HomeTwoController@createUser');
Route::post('/user/login/passcode', 'HomeTwoController@passcodeLogin');
Route::post('/user/contactformsend', 'HomeTwoController@contactformsend');
Route::post('/user/checkzipafterlogin', 'HomeTwoController@checkzipAfterGoogleLogin');

Route::post('/forgotpassword', 'Auth\ForgotPasswordController@webgetResetToken');
Route::get('/invoice', 'Pages\Account\Setting@invoice');
Route::get('user/invoice/{invoice}', 'Pages\Account\Setting@invoicePDF');
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
Route::get('/livetv/{channel}/{genre}', 'Pages\LiveTv@showChannel2')->middleware(['auth', 'subscribed']);
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
Route::get('/update/password', 'HomeController@updatePassword');
// Route::get('/checkAvailabilty', 'HomeController@checkAvailabilty');
Route::get('/checkAvailabilty', 'HomeController@checkAvailabilty2');
// Route::post('/checkAvailabiltyStore', 'Admin\DeclineZipsInquiryController@failedzipinquery');
// Route::post('/getzipcodes', 'Admin\DeclineZipsInquiryController@getZipcodes');
Route::get('/contactform', 'HomeController@contactform');
Route::get('/devices', 'HomeController@showSupportedDevices');
Route::get('/helpandsupport', 'HomeController@helpandsupp');
Route::delete('user/sessiondestroy/{id}', 'Pages\Account\Setting@sessiondestroy')->name('user.sessiondestroy');
Route::put('user/personaldevicename/{id}', 'Pages\Account\Setting@updatePersonalDeviceName')->name('user.personaldevicename');
Route::delete('user/subscriptiondestroy/{id}', 'Pages\Account\Setting@destroy')->name('user.subscriptiondestroy');
Route::put('/userupdatezip/{id}', 'Pages\Account\Setting@updateZipCode')->name('userupdatezip');
Route::put('/userdelete/{id}', 'Pages\Account\Setting@userDelete')->name('userdelete');
Route::put('/userupdatename/{id}', 'Pages\Account\Setting@updateName')->name('userupdatename');
Route::put('userupdatepass/{id}', 'Pages\Account\Setting@updatePass')->name('userupdatepass');
Route::put('userupdate/{id}', 'Pages\Account\Setting@update')->name('userupdate');
Route::post('/insertpay/{StripeplanId}', 'Pages\Payment@pay')->name('insertpay'); //send form data here
Route::post('favorite/', 'Pages\LiveTv@favoriteChannel');
Route::post('unfavorite/', 'Pages\LiveTv@unFavoriteChannel');
Route::get('my_favorites', 'Pages\LiveTv@myFavorites');
*/

Route::get('api/vuechannellist', 'EpgController@channellist');
Route::post('api/vueremovefav', 'EpgController@removeFav');
Route::get('api/vueprogramlistwithid', 'EpgController@programelistwithid');
Route::get('api/auth/tizenepg', 'TizenEPGController@channelDaysSchedule')->middleware(['jwt.verify', 'subscribed']);
Route::get('api/auth/v2/tizenepg', 'TizenEPGController@channelDaysScheduleV2')->middleware(['jwt.verify', 'subscribed']);
Route::get('api/auth/v2/tizenepgwithoffset', 'TizenEPGController@channelDaysScheduleV2offset')->middleware(['jwt.verify', 'subscribed']);
Route::get('api/auth/v2/tizenEPGschedule', 'TizenEPGController@channelDaysnew')->middleware(['jwt.verify', 'subscribed']);
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

// Route::get('/react', 'Pages\LiveTv@webReact');
// Route::get('/vue', 'EpgController@staticepg');
Route::get('/vuehtml', 'EpgController@vuehtml');
Route::get('ajax-check', 'Admin\SubscriptionController@ajaxCheck')->name('ajax-check'); //check coupon

Route::get('address-check', 'HomeController@addressCheck')->name('address-check');

Route::view('/search4', 'frontend.search4');
Route::get('/debug', 'Admin\DashboardController@debugmpd');
Route::get('/upcomingevent/{callsign}/{EventTitle}', 'SearchController@upcomingEvent');

// Route::get('/user/find', 'SearchController@searchevents');
Route::get('/user/find', 'SearchController@searchUsers');
Route::resource('bugreportings', 'Admin\BugReportingController');
Route::get('/dropzone1', 'RenameController@renameDisplay');
Route::post('dropzone1/store', 'RenameController@uploadImages')->name('dropzone.store');


Route::post('dropzone1/delete', 'RenameController@deleteImage')->name('dropzone.delete');

Route::get('dropzone', 'DropzoneController@index');

Route::post('dropzone/upload', 'DropzoneController@upload')->name('dropzone.upload');

Route::get('dropzone/fetch', 'DropzoneController@fetch')->name('dropzone.fetch');

Route::get('dropzone/delete', 'DropzoneController@delete')->name('dropzone.delete');

Route::get('/stripesync', function (Request $request) {

    Artisan::call('stripe:sync-renewals');
    // Artisan::call('stripe:check-customer-mode');

    return redirect()->back()->with('info', Artisan::output());
    //return Artisan::output();
});

Route::get('/sync-custom-subscription', function () {
    Artisan::call('custom-subscription:sync');
    return redirect()->back()->with('info', Artisan::output());
});

//  Route::get('/botdemo/chatserver1', 'HomeController@index')->name('home');

Route::post('admin/multisessiondestroy', 'Admin\UsersController@multisessiondestroy')->name('multisessiondestroy')->middleware(['auth']);
Route::post('web/login/entry/login', 'Admin\QRLoginTwoController@loginEntry'); //Check whether the login has been confirmed ,and return the token in response
Route::post('web/loginws', 'Admin\QRLoginTwoController@loginWS'); //Check when passcode hasbeen received
Route::delete('cancelsubscription/{id}', 'SubscriptionController@subscriptiondestroy');

Route::post('/stripe/webhook', 'StripeController@index');

Route::middleware(['auth', 'role:superadmin|admin|salesuser'])->prefix('salesuser')->name('salesuser')->group(function () {
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

Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    //iptv
    Route::get('/managed-iptv', 'iptvController@index');
    Route::post('/managed-iptv/submit_info', 'iptvController@submitInfo');
});


Route::get('/checkout/{master_id}', 'PaymentlinkController@checkout');
Route::post('checkout/backendpayment', 'PaymentlinkController@backendpayment');
Route::get('/success', 'PaymentlinkController@paymentsuccess');
Route::get('/fail', 'PaymentlinkController@paymentfail');

Route::any('/{any}', [AngularController::class, 'index'])
    ->where('any', '^(?!api|admin|stripe|hoa-sub-user-email|regenerate-passcode).*$')
    ->name('angular');

Route::get('/hoa-sub-user-email/verify/{id}/{hash}', [ApiHoaController::class, 'emailVerify'])->name('sub.user.email.verify');
Route::get('/regenerate-passcode/{token}', [ApiHoaController::class, 'regeneratePasscode'])->name('sub.user.regenerate.passcode');
// url from frontend
// Route::get('/sub-user-account/register/{id}/{hash}', [ApiHoaController::class, 'accountRegister'])->name('sub.user.account.register');

// cron route custom user

Route::post('/subscriptioncron', 'SubscriptionCronController@subscriptioncron');
// Route::get('users/updateall', 'Admin\UsersController@UpdateAll');
Route::get('/admin/passcode/{id}', function ($id) {
    return response()->json([
        'passcode' => HashUserID($id)
    ]);
});

Route::get('/admin/clear', function () {
    // Application cache
    Artisan::call('cache:clear');

    // Config cache
    Artisan::call('config:clear');

    // Route cache
    Artisan::call('route:clear');

    // View (Blade) cache
    Artisan::call('view:clear');

    return redirect()->route('admin.users')->with('info', "Cleared all cache successfully !");
});

// Route::get('/admin/php-info', function () {
//     phpinfo();
// });

 require_once __DIR__ . '/web-v2.php';
