<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CottageController;


Route::get('/', function () {
    return view('home');
});

Route::get('/chaty', function () {
    return redirect()->route('cottages.create');
})->name('cottages.index.temp');

Route::get('/chaty/vytvorit', [CottageController::class, 'create'])->name('cottages.create');
Route::post('/chaty', [CottageController::class, 'store'])->name('cottages.store');
