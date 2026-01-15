<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CottageController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('home');
});

Route::get('/chaty', [CottageController::class, 'index'])->name('cottages.index');
Route::get('/chaty/vytvorit', [CottageController::class, 'create'])->name('cottages.create');
Route::post('/chaty', [CottageController::class, 'store'])->name('cottages.store');

Route::delete('/chaty/{cottage}', [CottageController::class, 'destroy'])->name('cottages.destroy');
Route::get('/chaty/{cottage}/upravit', [CottageController::class, 'edit'])->name('cottages.edit');
Route::put('/chaty/{cottage}', [CottageController::class, 'update'])->name('cottages.update');


Route::get('/rezervacie', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('/rezervacie/vytvorit', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/rezervacie', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/rezervacie/{reservation}/upravit', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('/rezervacie/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

Route::delete('/rezervacie/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
