<?php

use App\Http\Controllers\AffiliateController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/updateUserInfo', [AffiliateController::class, 'updateUserInfo']);
Route::get('/getUserInfo', [AffiliateController::class, 'getUserInfo']);
Route::get('/updateAffiliateInfo', [AffiliateController::class, 'updateAffiliateInfo']);
Route::get('/updateBankInfo', [AffiliateController::class, 'updateBankInfo']);
Route::get('/getBankInfo', [AffiliateController::class, 'getBankInfo']);
Route::get('/addToken', [AffiliateController::class, 'addToken']);
Route::get('/listToken', [AffiliateController::class, 'listToken']);
Route::get('/socialLogin', [AffiliateController::class, 'socialLogin']);
Route::get('/loginToken', [AffiliateController::class, 'loginToken']);
Route::get('/addTicket', [AffiliateController::class, 'addTicket']);
Route::get('/paymentWithVNPay', [AffiliateController::class, 'paymentWithVNPay']);