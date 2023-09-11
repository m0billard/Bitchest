<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CryptoCurrencyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
Route::middleware('auth:sanctum')->controller(AdminController::class)->group(function () {
  Route::get('/getAllAdmin', 'getAllAdmin')->name('getAllAdmin');
  Route::post('/remove/admin/{id}', 'removeAdmin')->name('admin.remove');
  Route::post('/cash/update', 'cashUpdate')->name('cash.update');
});

Route::middleware('auth:sanctum')->controller(CustomerController::class)->group(function () {
  Route::get('/getAllCustomer', 'getAllCustomer')->name('getAllCustomer');
  Route::post('/remove/customer/{id}', 'removeCustomer')->name('customer.remove');
     Route::get('/customer-profile', 'CustomerController@profile')->name('customer-profile');
});

Route::middleware('auth:sanctum')->controller(CryptoCurrencyController::class)->group(function () {
  Route::get('/getAllCrypto', 'getAllCrypto')->name('getAllCrypto');
  Route::get('/getFirstCotation/{cryptoname}', 'getFirstCotation')->name('getFirstCotation');
  Route::get('/getCotationFor/{cryptoname}', 'getCotationFor')->name('getCotationFor');
  Route::post('/generate/transactions', 'generateTransactions')->name('generateTransactions');
  Route::post('/buy/crypto', 'buyCrypto')->name('buy.crypto');
  Route::post('/sell/crypto', 'sellCrypto')->name('sell.crypto');
});

Route::post('/tokens/create', function (Request $request) {
  $token = $request->user()->createToken($request->token_name);

  return ['token' => $token->plainTextToken];
});
