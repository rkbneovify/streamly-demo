<?php 

use App\Http\Controllers\Api\v2\ChannelController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2')->group(function () {

   Route::group(['prefix' => 'auth', 'middleware'=>['jwt.verify', 'hoa.subuser.subscription',  'stripe.sync', 'subscribed']], function () {
    Route::post('epg-guide', [ChannelController::class, 'epgGuideWithPagination']);
   });

});