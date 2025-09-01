<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MatrixController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

//MATRXI ROUTE
Route::get('matrix',  [MatrixController::class, 'index']);

// prefix admin and middleware admin-role
Route::group(['prefix' => 'admin', 'middleware' => ['admin-role']], function () {
    //SIGNUP ROUTE
    Route::get('dashboard', [DashboardController::class, 'index']);
});
