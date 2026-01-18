<?php

use App\Http\Controllers\PageController;
use App\Models\Cottage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CottageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/', function () {

    $topCottages = Cottage::query()
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->orderByDesc('reviews_avg_rating')
        ->orderByDesc('reviews_count')
        ->take(3)
        ->get();

    return view('home', compact('topCottages'));
});
/*
|--------------------------------------------------------------------------
| Public: Chaty (len prehliadanie)
|--------------------------------------------------------------------------
*/
Route::get('/o-nas', [PageController::class, 'about'])->name('about');
Route::get('/kontakt', [PageController::class, 'contact'])->name('contact');
Route::get('/chaty', [CottageController::class, 'index'])->name('cottages.index');

// Make cottage detail public so specific URIs like /chaty/vytvorit aren't captured by the
// wildcard route registered earlier. This prevents the "vytvorit" segment from being
// interpreted as a {cottage} parameter and avoids 404s when hitting the admin create page.
// Only match numeric IDs for the {cottage} parameter so words like "vytvorit" won't be
// treated as a cottage resource identifier.
Route::get('/chaty/{cottage}', [CottageController::class, 'show'])
    ->whereNumber('cottage')
    ->name('cottages.show');


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


    Route::post('/chaty/{cottage}/hodnotenia', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/hodnotenia/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/rezervacie/check', [ReservationController::class, 'checkAvailability'])
        ->name('reservations.check')
        ->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Admin-only: CRUD Chaty (create/edit/delete)
|--------------------------------------------------------------------------
| Pozn.: toto bude fungovať až po tom, čo pridáš middleware alias 'admin'
| (nižšie ti dávam aj kód middleware).
*/
// Admin-only
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/chaty/vytvorit', [CottageController::class, 'create'])->name('cottages.create');
    Route::post('/chaty', [CottageController::class, 'store'])->name('cottages.store');

    Route::get('/chaty/{cottage}/upravit', [CottageController::class, 'edit'])->name('cottages.edit');
    Route::put('/chaty/{cottage}', [CottageController::class, 'update'])->name('cottages.update');
    Route::delete('/chaty/{cottage}', [CottageController::class, 'destroy'])->name('cottages.destroy');
});
