<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\TransactionApiController;
use GuzzleHttp\Middleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:api');
Route::get('/profile', [AuthApiController::class, 'profile'])->middleware('auth:api');

Route::get('/category/all', [CategoryApiController::class, 'all'])->middleware('auth:api');
Route::post('/category', [CategoryApiController::class, 'store'])->middleware('auth:api');
Route::get('/category/{id}', [CategoryApiController::class, 'show'])->middleware('auth:api');
Route::put('/category/{id}', [CategoryApiController::class, 'update'])->middleware('auth:api');
Route::delete('/category/{id}', [CategoryApiController::class, 'destroy'])->middleware('auth:api');

Route::get('/product/all', [ProductApiController::class, 'all'])->middleware('auth:api');
Route::get('/product/{id}', [ProductApiController::class, 'show'])->middleware('auth:api');
Route::post('/product', [ProductApiController::class, 'store'])->middleware('auth:api');
Route::post('/product/update/{id}', [ProductApiController::class, 'update'])->middleware('auth:api');
Route::delete('/product/{id}', [ProductApiController::class, 'destroy'])->middleware('auth:api');

Route::get('/transaction/all', [TransactionApiController::class, 'all'])->middleware('auth:api');
Route::post('/transaction/import', [TransactionApiController::class, 'import'])->middleware('auth:api');