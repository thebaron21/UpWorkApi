<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CompanyController;
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


Route::post( '/login', [UserController::class , 'login'] );
Route::post( '/register' ,[UserController::class , 'register'] );
Route::get( '/get-profile' , [UserController::class, 'findProfile'] )->middleware('auth:api');
Route::get('/get-user/{id}',[UserController::class,'findUser']);

Route::post('/upload-image',[CompanyController::class,'index']);

