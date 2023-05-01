<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;

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
    return view('address');
})->name('address_view');

Route::get('/weather', function () {
    return view('weather');
})->name('weather_view');

Route::get('/statistics', [VisitorController::class, 'getStatsVisitor'])->name('statistics_view');

Route::post('/visitors/create', [VisitorController::class, 'createVisitor'])->name('visitors_create');
