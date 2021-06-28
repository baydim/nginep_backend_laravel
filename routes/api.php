<?php

use App\Http\Controllers\Auth\Auth_controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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



///auth
Route::post('/login', [Auth_controller::class, 'login']);
Route::post('/regis', [Auth_controller::class, 'regis']);
Route::post('/logout', [Auth_controller::class, 'logout']);
Route::post('/logoutall', [Auth_controller::class, 'logoutall']);


///route groub sactum
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/hotel', [Hotel_Controller::class, 'allHotel']);
    Route::get('/hotel/id={id}', [Hotel_Controller::class, 'detailHotel']);
    Route::get('/hotel/delete/id={id}', [Hotel_Controller::class, 'deletHotel']);
    Route::post('hotel/add/', [Hotel_Controller::class, 'addHotel']);
    
    Route::get('/room', [Room_Controller::class, 'allRoom']);
    Route::get('/room/id={id}', [Room_Controller::class, 'detailRoom']);
    Route::post('/room/add/', [Room_Controller::class, 'addRoom']);
});

///room
