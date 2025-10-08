<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserTournamentController;
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

Route::get('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/authenticate', [AdminAuthController::class, 'authenticate'])->name('admin.authenticate');
Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// prefix admin and middleware admin-role
Route::group(['prefix' => 'admin', 'middleware' => ['admin-role']], function () {
    //SIGNUP ROUTE
    Route::get('dashboard', [DashboardController::class, 'index']);

    // tournament routes
    Route::get('tournaments', [TournamentController::class, 'index'])->name('admin.tournament');
    Route::get('tournament/create', [TournamentController::class, 'create'])->name('admin.tournament.create');
    Route::post('tournament/store', [TournamentController::class, 'store'])->name('admin.tournament.store');
    Route::get('tournament/edit/{id}', [TournamentController::class, 'edit'])->name('admin.tournament.edit');
    Route::post('tournament/update', [TournamentController::class, 'update'])->name('admin.tournament.update');
    Route::get('tournament/delete/{id}', [TournamentController::class, 'delete'])->name('admin.tournament.delete');
    Route::get('tournament/details/{id}', [TournamentController::class, 'details'])->name('admin.tournament.details');
    Route::get('tournament/results/{id}', [TournamentController::class, 'results'])->name('admin.tournament.results');

    // tournament routes
    Route::get('games', [GameController::class, 'index'])->name('admin.game');
    Route::get('game/create', [GameController::class, 'create'])->name('admin.game.create');
    Route::post('game/store', [GameController::class, 'store'])->name('admin.game.store');
    Route::get('game/edit/{id}', [GameController::class, 'edit'])->name('admin.game.edit');
    Route::post('game/update', [GameController::class, 'update'])->name('admin.game.update');
    Route::post('game/delete/{id}', [GameController::class, 'delete'])->name('admin.game.delete');

});

// user authentication routes
Route::get('user/sign-up', [UserAuthController::class,'signup'])->name('user.signup');
Route::post('user/register', [UserAuthController::class, 'register'])->name('user.register');
Route::get('user/login', [UserAuthController::class, 'login'])->name('user.login');
Route::post('user/authenticate', [UserAuthController::class, 'authenticate'])->name('user.authenticate');
Route::get('user/logout', [UserAuthController::class, 'logout'])->name('user.logout');

Route::get('tournaments', [UserTournamentController::class, 'index'])->name('tournament');

Route::group(['middleware' => ['user-role']], function () {
    Route::get('waiting-area/{id}', [UserTournamentController::class, 'waiting'])->name('waiting');
    Route::get('play-tournament/{id}', [UserTournamentController::class, 'play'])->name('play');
    Route::get('play-game', [UserTournamentController::class, 'playGame'])->name('play.game');
    Route::post('round/submit-score', [UserTournamentController::class, 'submitScore'])->name('round.submit.score');
});

