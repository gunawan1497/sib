<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;

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
    return view('welcome');
});

Route::get('/test', function () {
    // return view('test', ['nama' => 'Gunawan Setyo', 'umur' => 24]);
    return view('test')->with('nama', 'Gunawan Setyo')->with('umur', 24);
});

Route::middleware('auth')->group(function() {

Route::get('/admin', [DashboardController::class, 'index']);
// ->middleware('auth');

Route::get('/admin/category/create', [CategoryController::class, 'create']);
Route::post('/admin/category', [CategoryController::class, 'store']);
Route::get('/admin/category', [CategoryController::class, 'index']);
Route::get('/admin/category/{category}', [CategoryController::class, 'show']);
Route::get('/admin/category/{category}/edit', [CategoryController::class, 'edit']);
Route::put('/admin/category/{category}', [CategoryController::class, 'update']);
Route::delete('/admin/category/{category}/delede', [CategoryController::class, 'destroy'])->name('category.destroy');

# PRODUCTS
// Route::get('/admin/product', [ProductController::class, 'index']);
Route::resource('admin/product', ProductController::class);

Route::get('admin/transaction', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('admin/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('admin/transaction', [TransactionController::class, 'import'])->name('transaction.import');
Route::get('admin/transaction/download', [TransactionController::class, 'export'])->name('transaction.export');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/category/all', [CategoryApiController::class, 'all'])->middleware('auth:api');
