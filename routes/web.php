<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ProviderAuthController;
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
    return view('welcome');
});
Auth::routes();

//Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [AffiliateController::class, 'affiliate'])->name('dashboard');
    Route::get('/info', [AffiliateController::class, 'update'])->name('info');
});
//KC login
Route::get('/redirect/{provider?}', [ProviderAuthController::class, 'redirectToProvider'])->name('login');
Route::get('/callback', [ProviderAuthController::class, 'callbackFunction']);
Route::get('/kc-logout', [ProviderAuthController::class, 'providerLogout'])->name('kc-logout');
//Go to product via affiliate link
Route::get('/affiliate/{link?}', [AffiliateController::class, 'gotoProductLink'])->name('goto-product-link');
