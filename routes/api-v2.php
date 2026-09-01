<?php

use App\Http\Controllers\Api\V2\ChannelController;
use App\Http\Controllers\Api\V2\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2')->group(function () {

    Route::group(['prefix' => 'auth', 'middleware' => ['jwt.verify', 'hoa.subuser.subscription',  'stripe.sync', 'subscribed']], function () {
        Route::post('epg-guide', [ChannelController::class, 'epgGuideWithPagination']);
        Route::post('epg-guide-test', [ChannelController::class, 'epgGuide']);
    });
    Route::post('/subscription/check', [SubscriptionController::class, 'subscriptionCheck'])->middleware(['jwt.verify']);
});
