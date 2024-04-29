<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FrontendOfferController;
use App\Http\Controllers\Admin\PackageOfferController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/loginPost', [AdminLoginController::class, 'loginPost']);
Route::get('logout/', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::get('/dashbord', [AdminController::class, 'index'])->name('admin.dashboard.admin');
Route::get('/deletehotel/{id}', [FrontendOfferController::class, 'deleteHotelOffer']);
Route::get('/edithotel/{id}', [FrontendOfferController::class, 'editHotelData']);
Route::get('/hotel-offer', [FrontendOfferController::class, 'hotelOffer']);
Route::post('/posthotel_offer', [FrontendOfferController::class, 'postHotelOffer']);
Route::post('/updatePostHotelOffer', [FrontendOfferController::class, 'updatePostHotelOffer']);

Route::get('/airline-offer', [FrontendOfferController::class, 'airlineOffer']);
Route::get('/airline-offer-edit/{id}', [FrontendOfferController::class, 'airlineOfferEdit']);
Route::get('/airline-offer-delete/{id}', [FrontendOfferController::class, 'airlineOfferDelete']);
Route::post('/postAirline_offer', [FrontendOfferController::class, 'postAirlineOffer']);
Route::post('/postAirlineofferUpdate', [FrontendOfferController::class, 'postAirlineofferUpdate']);

Route::get('/offer-package', [PackageOfferController::class, 'offerPackage']);
Route::get('/addofferpackage', [PackageOfferController::class, 'addOfferPackage']);
Route::get('/deleteofferpackage/{id}', [PackageOfferController::class, 'deleteOfferPackage']);
Route::get('/editofferpackage/{id}', [PackageOfferController::class, 'editOfferPackage']);
Route::post('/postOfferPackages', [PackageOfferController::class, 'postOfferPackages']);
Route::post('/postOfferPackagesUpdate', [PackageOfferController::class, 'postOfferPackagesUpdate']);



