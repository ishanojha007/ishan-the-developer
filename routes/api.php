<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| CALL WHEN ANY INVALID URL CALLED
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->json([
        'ResponseCode'  => 404,
        'status'        => False,
        'message'       => 'URL not found as you looking'
    ]);
});

/*
|--------------------------------------------------------------------------
| AUTHORISATION FAILED ROUTE
|--------------------------------------------------------------------------
*/

Route::get('unauthorizedAccess', function () {
    return response()->json([
        'ResponseCode'  => 401,
        'Status'        => False,
        'Message'       => 'YOU ARE NOT UNAUTHORIZED TO ACCESS THIS URL, PLEASE LOGIN AGAIN'
    ]);
})->name('login');



/*
|--------------------------------------------------------------------------
| AUTH LOGINROUTE
|--------------------------------------------------------------------------
*/

Route::post('login', [ApiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| AUTHORISATION ROUTE
|--------------------------------------------------------------------------
 */
Route::middleware('auth:api')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | PROFILE, WALLET, COOKIE PURCHASE ROUTE
    |--------------------------------------------------------------------------
    */
    Route::controller(ApiController::class)->group(function () {
        Route::get('profile', 'profile');
        Route::post('add_amount_in_wallet', 'addAmountInWallet');
        Route::post('buy_cookie', 'buyCookie');
    });
});
