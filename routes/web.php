<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CottageController;


Route::get('/', function () {
    return view('home');
});

Route::get('/chaty', [CottageController::class, 'index'])->name('cottages.index');
Route::get('/chaty/vytvorit', [CottageController::class, 'create'])->name('cottages.create');
Route::post('/chaty', [CottageController::class, 'store'])->name('cottages.store');

Route::delete('/chaty/{cottage}', [CottageController::class, 'destroy'])->name('cottages.destroy');
Route::get('/chaty/{cottage}/upravit', [CottageController::class, 'edit'])->name('cottages.edit');
Route::put('/chaty/{cottage}', [CottageController::class, 'update'])->name('cottages.update');
