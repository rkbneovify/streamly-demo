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



