<?php

use App\Http\Controllers\IndexPageController;
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

Route::get('/', [IndexPageController::class, 'index'])->name('index-page');
Route::get('/{league}/{game}', [IndexPageController::class, 'odds'])->name('odds-page');
Route::get('/{league}', [IndexPageController::class, 'games'])->name('games-page');
