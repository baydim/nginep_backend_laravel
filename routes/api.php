<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
// use App\Http\Controllers\HotelFacilController;
use App\Http\Controllers\RoomController;
// use App\Http\Controllers\mulimage;



use App\Http\Controllers\Hotel_Controller;
use App\Http\Controllers\Room_Controller;

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


////hotel
// Route::get('/hotel', [HotelController::class, 'index']);
// Route::get('/hotel/idhotel={id}', [HotelController::class, 'hoteldetail']);
// Route::get('hotel/cari/alamat={alamat}', [HotelController::class, 'carihotel']);
// Route::post('hotel/add/', [HotelController::class, 'addhotel']);


// /////roomcontroller
// Route::get('/hotel/rooms/idrooms={id}', [RoomController::class, 'detail']);
// Route::post('/hotel/rooms/add', [RoomController::class, 'addroom']);
// Route::get('/hotel/rooms/delete/idrooms={id}', [RoomController::class, 'deleteroom']);


// /////hotelfacilcontroller
// Route::get('/hotel/facil/hotel_id={hotel_id}', [HotelFacilController::class, 'facil']);
// Route::get('/facil/hapus/facil_id={facil_id}', [HotelFacilController::class, 'deletfacil']);


// //tesmultiple
// Route::post('/mul', [mulimage::class, 'mul']);



////new
Route::get('/hotel', [Hotel_Controller::class, 'allHotel']);
Route::get('/hotel/id={id}', [Hotel_Controller::class, 'detailHotel']);
Route::get('/hotel/delete/id={id}', [Hotel_Controller::class, 'deletHotel']);
Route::post('hotel/add/', [Hotel_Controller::class, 'addHotel']);



///room
Route::get('/room', [Room_Controller::class, 'allRoom']);
Route::get('/room/id={id}', [Room_Controller::class, 'detailRoom']);
Route::post('/room/add/', [Room_Controller::class, 'addRoom']);
