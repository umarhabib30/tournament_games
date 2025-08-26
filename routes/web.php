<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SignUpController;
use App\Http\Controllers\MatrixController;
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

Route::get('/', function () {
    return view('welcome');
});


//DASHBOARD ROUTE
Route::get('admin/dashboard', [DashboardController::class, 'index']);

//LOGIN ROUTE
Route::get('login',  [LoginController::class, 'index']);



//MATRXI ROUTE
Route::get('matrix',  [MatrixController::class, 'index']);
