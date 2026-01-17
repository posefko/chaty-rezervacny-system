<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CottageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Public: Chaty (len prehliadanie)
|--------------------------------------------------------------------------
*/
Route::get('/chaty', [CottageController::class, 'index'])->name('cottages.index');
// (Voliteľné do budúcna) detail chaty:
// Route::get('/chaty/{cottage}', [CottageController::class, 'show'])->name('cottages.show');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/chaty/filter', [CottageController::class, 'filter'])->name('cottages.filter');

/*
|--------------------------------------------------------------------------
| Auth-only: Rezervácie (iba prihlásený používateľ)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/rezervacie', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/rezervacie/vytvorit', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/rezervacie', [ReservationController::class, 'store'])->name('reservations.store');

    Route::get('/rezervacie/{reservation}/upravit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/rezervacie/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::delete('/rezervacie/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::get('/chaty/{cottage}', [CottageController::class, 'show'])->name('cottages.show');

    Route::post('/chaty/{cottage}/hodnotenia', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/hodnotenia/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin-only: CRUD Chaty (create/edit/delete)
|--------------------------------------------------------------------------
| Pozn.: toto bude fungovať až po tom, čo pridáš middleware alias 'admin'
| (nižšie ti dávam aj kód middleware).
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/chaty/vytvorit', [CottageController::class, 'create'])->name('cottages.create');
    Route::post('/chaty', [CottageController::class, 'store'])->name('cottages.store');

    Route::get('/chaty/{cottage}/upravit', [CottageController::class, 'edit'])->name('cottages.edit');
    Route::put('/chaty/{cottage}', [CottageController::class, 'update'])->name('cottages.update');

    Route::delete('/chaty/{cottage}', [CottageController::class, 'destroy'])->name('cottages.destroy');
});
