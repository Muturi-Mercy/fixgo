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

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/count', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    })->name('notifications.count');
});

//Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminDashboardController::class, 'viewUser'])->name('users.view');
    Route::patch('/users/{id}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');

    // Mechanics
    Route::get('/mechanics', [AdminDashboardController::class, 'mechanics'])->name('mechanics');
    Route::get('/mechanics/{id}', [AdminDashboardController::class, 'viewMechanic'])->name('mechanics.view');
    Route::patch('/mechanics/{id}/approve', [AdminDashboardController::class, 'approveMechanic'])->name('mechanics.approve');
    Route::patch('/mechanics/{id}/reject', [AdminDashboardController::class, 'rejectMechanic'])->name('mechanics.reject');
    Route::patch('/mechanics/{id}/undo-rejection', [AdminDashboardController::class, 'undoRejection'])->name('mechanics.undo-rejection');
    Route::patch('/mechanics/{id}/toggle-status', [AdminDashboardController::class, 'toggleMechanicStatus'])->name('mechanics.toggle-status');

    // Requests
    Route::get('/requests', [AdminDashboardController::class, 'requests'])->name('requests');
    Route::get('/requests/{id}', [AdminDashboardController::class, 'viewRequest'])->name('requests.view');

    // Categories
    Route::get('/categories', [AdminDashboardController::class, 'categories'])->name('categories');
    Route::post('/categories/service', [AdminDashboardController::class, 'storeServiceCategory'])->name('categories.service.store');
    Route::delete('/categories/service/{id}', [AdminDashboardController::class, 'deleteServiceCategory'])->name('categories.service.delete');
    Route::post('/categories/vehicle', [AdminDashboardController::class, 'storeVehicleCategory'])->name('categories.vehicle.store');
    Route::delete('/categories/vehicle/{id}', [AdminDashboardController::class, 'deleteVehicleCategory'])->name('categories.vehicle.delete');

    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');

    // Reviews
    Route::get('/reviews', [AdminDashboardController::class, 'reviews'])->name('reviews');
    Route::delete('/reviews/{id}', [AdminDashboardController::class, 'deleteReview'])->name('reviews.delete');

    // Announcements
    Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('announcements');
    Route::post('/announcements', [AdminDashboardController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AdminDashboardController::class, 'deleteAnnouncement'])->name('announcements.delete');

    // Settings
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');

    // Analytics API
    Route::get('/analytics/requests', [AdminDashboardController::class, 'requestsAnalytics'])->name('analytics.requests');
});

//Mechanic Routes

Route::middleware(['auth','mechanic'])->prefix('mechanic')->name('mechanic.')->group(function(){
    Route::get('/dashboard',[MechanicDashboardController::class,'index'])->name('dashboard');
    Route::get('/service-requests',[MechanicDashboardController::class,'serviceRequests'])->name('service-requests');
    Route::get('/my-jobs', [MechanicDashboardController::class, 'myJobs'])->name('my-jobs');
    Route::get('/earnings', [MechanicDashboardController::class, 'earnings'])->name('earnings');
    Route::get('/portfolio', [MechanicDashboardController::class, 'portfolio'])->name('portfolio');
    Route::get('/portfolio/create', [MechanicDashboardController::class, 'createPortfolio'])->name('portfolio.create');
    Route::post('/portfolio', [MechanicDashboardController::class, 'storePortfolio'])->name('portfolio.store');
    Route::delete('/portfolio/{id}', [MechanicDashboardController::class, 'deletePortfolio'])->name('portfolio.delete');
    Route::get('/reviews', [MechanicDashboardController::class, 'reviews'])->name('reviews');
    Route::get('/profile', [MechanicDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [MechanicDashboardController::class, 'updateProfile'])->name('update-profile');
    Route::patch('/profile/password', [MechanicDashboardController::class, 'updatePassword'])->name('update-password');
    Route::post('/profile/photo', [MechanicDashboardController::class, 'updatePhoto'])->name('update-photo');
    Route::patch('/availability', [MechanicDashboardController::class, 'updateAvailability'])->name('update-availability');
    Route::patch('/request/{id}/accept', [MechanicDashboardController::class, 'acceptRequest'])->name('accept-request');
    Route::patch('/request/{id}/decline', [MechanicDashboardController::class, 'declineRequest'])->name('decline-request');
    Route::patch('/request/{id}/status', [MechanicDashboardController::class, 'updateRequestStatus'])->name('update-request-status');
    Route::get('/portfolio/{id}', [MechanicDashboardController::class, 'viewPortfolio'])->name('portfolio.view');
    Route::get('/portfolio/{id}/edit', [MechanicDashboardController::class, 'editPortfolio'])->name('portfolio.edit');
    Route::post('/portfolio/{id}/update', [MechanicDashboardController::class, 'updatePortfolio'])->name('portfolio.update');
    Route::post('/update-location', [MechanicDashboardController::class, 'updateLocation'])->name('update-location'); 
    Route::get('/chat/{requestId}', [MechanicDashboardController::class, 'chat'])->name('chat');
    Route::post('/chat/{requestId}/send', [MechanicDashboardController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{requestId}/messages', [MechanicDashboardController::class, 'getMessages'])->name('chat.messages');
    Route::get('/driver/{id}', [MechanicDashboardController::class, 'viewDriver'])->name('view-driver');
    Route::get('/notifications', [MechanicDashboardController::class, 'notifications'])->name('notifications');
    Route::patch('/notifications/mark-all-read', [MechanicDashboardController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/settings', [MechanicDashboardController::class, 'settings'])->name('settings');
    Route::patch('/settings', [MechanicDashboardController::class, 'updateSettings'])->name('update-settings');
    Route::get('/notification/{id}', [MechanicDashboardController::class, 'viewNotification'])->name('notification.view');
    Route::patch('/notifications/mark-all-read', [MechanicDashboardController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::patch('/notification/{id}/read', [MechanicDashboardController::class, 'markRead'])->name('notifications.mark-read');
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
    Route::get('/request-assistance/{mechanic_id?}', [UserDashboardController::class, 'requestAssistance'])->name('request-assistance');
    Route::patch('/request/{id}/cancel', [UserDashboardController::class, 'cancelRequest'])->name('cancel-request');
    Route::post('/rate-request', [UserDashboardController::class, 'rateRequest'])->name('rate-request');
    Route::get('/request-details/{id}/cancel', [UserDashboardController::class, 'requestDetails'])->name('request-details');
    Route::get('/request/{id}',[UserDashboardController::class,'viewRequest'])->name('request-details-page');
    Route::post('/favourite/{id}', [UserDashboardController::class, 'toggleFavourite'])->name('toggle-favourite');
    Route::patch('/profile', [UserDashboardController::class, 'updateProfile'])->name('update-profile');
    Route::patch('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('update-password');
    Route::post('/profile/photo', [UserDashboardController::class, 'updatePhoto'])->name('update-photo');
    Route::get('/notifications',[UserDashboardController::class,'notifications'])->name('notifications');
    Route::patch('/notifications/mark-all-read',[UserDashboardController::class,'markAllRead'])->name('notifications.mark-all-read');
    Route::patch('/notifications/{id}/read',[UserDashboardController::class,'markRead'])->name('notifications.mark-read');
    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
    Route::patch('/settings', [UserDashboardController::class, 'updateSettings'])->name('update-settings');
    Route::post('/sos', [UserDashboardController::class, 'sos'])->name('sos');
    Route::get('/mechanic-location/{id}', [UserDashboardController::class, 'getMechanicLocation'])->name('mechanic-location');
    Route::get('/chat/{requestId}', [UserDashboardController::class, 'chat'])->name('chat');
    Route::post('/chat/{requestId}/send', [UserDashboardController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{requestId}/messages', [UserDashboardController::class, 'getMessages'])->name('chat.messages');
    Route::get('/notification/{id}', [UserDashboardController::class, 'viewNotification'])->name('notification.view');
    


});


require __DIR__.'/auth.php';