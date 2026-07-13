<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Mechanic\MechanicDashboardController;
use Illuminate\Support\Facades\Route;

//Welcome page
Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//Admin Routes

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard',[AdminDashboardController::class,'index'])->name('dashboard');
});

//Mechanic Routes

Route::middleware(['auth','mechanic'])->prefix('mechanic')->name('mechanic.')->group(function(){
    Route::get('/dashboard',[MechanicDashboardController::class,'index'])->name('dashboard');
});

//User Routes

Route::middleware(['auth','user.role'])->prefix('user')->name('user.')->group(function(){
    Route::get('/dashboard',[UserDashboardController::class,'index'])->name('dashboard');
    Route::get('/request-assistance',[UserDashboardController::class,'requestAssistance'])->name('request-assistance');
    Route::get('/my-requests', [UserDashboardController::class, 'myRequests'])->name('my-requests');
    Route::get('/mechanics',[UserDashboardController::class,'mechanics'])->name('mechanics');
    Route::get('/mechanics/{id}',[UserDashboardController::class,'mechanicProfile'])->name('mechanic-profile');
    Route::get('/track/{id}',[UserDashboardController::class,'trackMechanic'])->name('track');
    Route::get('/favourites',[UserDashboardController::class,'favourites'])->name('favourites');
    Route::get('/notifications',[UserDashboardController::class,'notifications'])->name('notifications');
    Route::get('/profile',[UserDashboardController::class,'profile'])->name('profile');
    Route::post('/request-assistance',[UserDashboardController::class,'storeRequest'])->name('store-request');
    Route::patch('/request/{id}/cancel', [UserDashboardController::class, 'cancelRequest'])->name('cancel-request');
    Route::post('/rate-request', [UserDashboardController::class, 'rateRequest'])->name('rate-request');
    Route::get('/request-details/{id}', [UserDashboardController::class, 'requestDetails'])->name('request-details');
    Route::post('/favourite/{id}', [UserDashboardController::class, 'toggleFavourite'])->name('toggle-favourite');
    Route::patch('/profile', [UserDashboardController::class, 'updateProfile'])->name('update-profile');
    Route::patch('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('update-password');
    Route::post('/profile/photo', [UserDashboardController::class, 'updatePhoto'])->name('update-photo');
});


require __DIR__.'/auth.php';