<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CryptoCurrencyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\UserRole;
use Inertia\Inertia;

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
  return Inertia::render('Auth/Login', [

    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard', [
    'checkIfAdmin' => UserRole::checkIfAdmin()
  ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
  return Inertia::render('AdminSettings/Admin', [
    'checkIfAdmin' => UserRole::checkIfAdmin(),
    'tableData' => AdminController::getAllAdmin(),
    'type' => 'admin'
  ]);
})->middleware(['auth', 'verified'])->name('admin-settings');

Route::get('/customer', function () {
  return Inertia::render('AdminSettings/Customer', [
    'checkIfAdmin' => UserRole::checkIfAdmin(),
    'tableData' => CustomerController::getAllCustomer(),
    'type' => 'customer'
  ]);
})->middleware(['auth', 'verified'])->name('customer-management');

Route::get('/crypto', function () {
  return Inertia::render('AdminSettings/Crypto', [
    'checkIfAdmin' => UserRole::checkIfAdmin(),
    'tableData' => CryptoCurrencyController::getAllCrypto(),
  ]);
})->middleware(['auth', 'verified'])->name('customer-management');

Route::get('/mycrypto', function () {
  return Inertia::render('AdminSettings/MyCrypto', [
    'checkIfAdmin' => UserRole::checkIfAdmin(),
    'tableData' => CryptoCurrencyController::getAllHoldings(),
    'soldTableData' => CryptoCurrencyController::getAllSoldHoldings(),
  ]);
})->middleware(['auth', 'verified'])->name('customer-management');
Route::middleware('auth')->group(function () {
  Route::get('/crypto/{cryptocode}', [CryptoCurrencyController::class, 'transaction'])->name('transaction');
});
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
