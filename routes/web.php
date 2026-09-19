<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [PropertyController::class, 'index'])
    ->name('home');

// Property details
Route::get('/property/{id}', [PropertyController::class, 'show'])
    ->name('property.show');

// Search
Route::get('/properties/search', [PropertyController::class, 'search'])
    ->name('properties.search');

// Live search (AJAX)
Route::get('/search-live', [PropertyController::class, 'liveSearch']);


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.submit');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [AuthController::class, 'profile'])
        ->name('profile');


    /*
    |--------------------------------------------------------------------------
    | Host / Property Management
    |--------------------------------------------------------------------------
    */

    Route::get('/host', [PropertyController::class, 'create'])
        ->name('host.page');

    Route::post('/property/store', [PropertyController::class, 'store'])
        ->name('property.store');

    Route::post('/property/{id}/update', [PropertyController::class, 'update'])
        ->name('property.update');


    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    Route::prefix('booking')->group(function () {

        // Booking page
        Route::get('/{id}/create', [BookingController::class, 'create'])
            ->name('booking.create');

        // Create booking
        Route::post('/{id}', [BookingController::class, 'store'])
            ->name('booking.store');

        // Host confirms booking
        Route::post('/{id}/confirm', [BookingController::class, 'confirm'])
            ->name('booking.confirm');

        // User cancels booking
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])
            ->name('booking.cancel');
    });


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::get('/payment/{bookingId}', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::post('/payment/success/{id}', [PaymentController::class, 'paymentSuccess'])
        ->name('payment.success');
});


/*
|--------------------------------------------------------------------------
| Chatbot
|--------------------------------------------------------------------------
*/

Route::post('/chatbot', [ChatbotController::class, 'handle'])
    ->middleware('throttle:20,1');