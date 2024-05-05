<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Airline\AirportiatacodesController;
use App\Http\Controllers\helper\HelperController;
use App\Http\Controllers\helper\AirlineCodeController;
use App\Http\Controllers\Airline\Amadeus\{Air_SellFromRecommendationController ,DomesticPnrAddMultiElementsController ,PNR_AddMultiElementsController ,SearchflightController};

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

Route::post('/review' , [Air_SellFromRecommendationController::class, 'Air_SellFromRecommendation']);

Route::group(['prefix' => 'flight'], function () {
    Route::get('/search', [SearchFlightController::class, 'Fare_MasterPricerTravelBoardSearch'])->name('search-flight-results');
    Route::post('/review', [Air_SellFromRecommendationController::class, 'Air_SellFromRecommendation'])->name('flight-review');
    Route::post('/payment', [PNR_AddMultiElementsController::class, 'PNR_AddMultiElements'])->name('booking.flight-booking');
    Route::post('/booking-roundtrip-domestic', [DomesticPnrAddMultiElementsController::class, 'DomPnrAddMultiElements'])->name('booking.flight-booking-dom');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/airlinecode', [AirlineCodeController::class, 'AirlineCode'])->name('ailine_code');
Route::get('/phonecode', [HelperController::class, 'phonecode'])->name('phone.code');
Route::get('/airlinecodes', [AirportiatacodesController::class, 'searchAirIataCode'])->name('cities.search');
Route::get('/country-code', [AirportiatacodesController::class, 'searchCountryCode'])->name('country-code-search');
Route::get('/country-iso', [AirportiatacodesController::class, 'searchCountryIso'])->name('country-iso-search');
Route::get('/Hotel-city', [AirportiatacodesController::class, 'HotelCity'])->name('HotelCity');

Route::controller(ImageController::class)->group(function () {
    Route::post('/image/{entity}', 'store')->name('api.image.store');
});
//  --------------- BLOG ------------------
Route::controller(BlogController::class)->group(function () {
    Route::get('blogs', 'index')->name('api.blog.index');
    Route::prefix('blog')->group(function () {
        Route::get('create', 'create')->name('api.blog.create');
        Route::post('store',  'store')->name('api.blog.store');
        Route::get('{id}/edit',  'edit')->name('api.blog.edit');
        Route::get('{id}',  'show')->name('api.blog.show');
        Route::put('{id}/update',  'update')->name('api.blog.update');
        Route::delete('{id}',  'destroy')->name('api.blog.delete');
    });
});
