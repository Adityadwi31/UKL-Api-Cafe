<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// meja
Route::get('/getmeja',[MejaController::class,'getmeja']);
Route::get('/getmejakosong',[MejaController::class,'mejatersedia']);
Route::get('/getmeja/{id}',[MejaController::class,'show']);
Route::post('/createmeja',[MejaController::class,'createmeja']);
Route::put('/updatemeja/{id}',[MejaController::class,'update']);
Route::delete('/deletemeja/{id}',[MejaController::class,'destroy']);

// menu
Route::get('/getmenu',[MenuController::class,'getmenu']);
Route::get('/getmenu/{id}',[MenuController::class,'show']);
Route::post('/createmenu',[MenuController::class,'store']);
Route::post('/updatefoto/{id}',[MenuController::class,'updatephoto']);
Route::put('/updatemenu/{id}',[MenuController::class,'updatemenu']);
Route::delete('/deletemenu/{id}',[MenuController::class,'destroy']);

// user
Route::get('/getuser',[UserController::class,'index']);
Route::post('/login',[UserController::class,'login']);
Route::get('/getuser/{id}',[UserController::class,'show']);
Route::get('/getkasir',[UserController::class,'getkasir']);
Route::post('/createuser',[UserController::class,'store']);
Route::put('/updateuser/{id}',[UserController::class,'update']);
Route::delete('/deleteuser/{id}',[UserController::class,'destroy']);

// transaksi
Route::get('/gethistori',[TransaksiController::class,'gethistory']);
Route::get('/gethistori/{id}',[TransaksiController::class,'selecthistori']);

Route::get('/gettransaksi',[TransaksiController::class,'gettransaksi']);
Route::get('/gettransaksi/{id}',[TransaksiController::class,'selecttransaksi']);

Route::get('/getday/{date}',[TransaksiController::class,'getdate']);
Route::get('/getmonth/{month}',[TransaksiController::class,'getmonth']);

Route::post('/pesan',[TransaksiController::class,'tambahpesanan']);
Route::put('/checkout',[TransaksiController::class,'checkout']);
Route::put('/done/{id}',[TransaksiController::class,'donetransaksi']);


Route::get('/ongoing',[TransaksiController::class,'ongoing']);
Route::get('/ongoing_transaksi/{id}',[TransaksiController::class,'getongoingtransaksi']);
Route::get('/getcart',[TransaksiController::class,'getcart']);
Route::get('/total/{code}',[TransaksiController::class,'total']);
Route::get('/totalharga/{id}',[TransaksiController::class,'totalharga']);
Route::delete('/deletetransaksi/{id}',[TransaksiController::class,'destroy']);





