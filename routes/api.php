<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelFacilController;
use App\Http\Controllers\RoomController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/hotel', [HotelController::class, 'index']);
Route::get('/hotel/idhotel={id}', [HotelController::class, 'hoteldetail']);
/////cari hotel
Route::get('hotel/cari/alamat={alamat}', [HotelController::class, 'carihotel']);
///add hotel
Route::post('hotel/add/', [HotelController::class, 'addhotel']);


/////roomcontroller
Route::get('/hotel/rooms/idrooms={id}', [RoomController::class, 'detail']);


/////hotelfacilcontroller
Route::get('hotel/facil/hotel_id={hotel_id}', [HotelFacilController::class, 'facil']);
Route::get('/facil/hapus/facil_id={facil_id}', [HotelFacilController::class, 'deletfacil']);
