<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ana sayfa
Route::get('/', [HomeController::class, 'index'])->name('home');

// Arama
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Hizmetler
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
});

// Kategoriler
Route::get('/category/{slug}', [ServiceController::class, 'category'])->name('category.show');

// Rezervasyonlar (Giriş yapmış kullanıcılar için)
Route::middleware('auth')->group(function () {
    // Booking Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/services/{service}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/services/{service}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::patch('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/photo', [App\Http\Controllers\UserProfileController::class, 'deleteProfilePhoto'])->name('profile.delete-photo');
    Route::get('/users/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');
    
    // Review Routes
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/bookings/{booking}/review', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
    Route::patch('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::delete('/reviews/{review}/image/{imageIndex}', [App\Http\Controllers\ReviewController::class, 'deleteImage'])->name('reviews.delete-image');
    
    // Provider Panel Routes (Service Provider Only)
    Route::prefix('provider')->name('provider.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Provider\ProviderPanelController::class, 'dashboard'])->name('dashboard');
        Route::get('/services', [App\Http\Controllers\Provider\ProviderPanelController::class, 'services'])->name('services');
        Route::get('/services/create', [App\Http\Controllers\Provider\ProviderPanelController::class, 'createService'])->name('services.create');
        Route::post('/services', [App\Http\Controllers\Provider\ProviderPanelController::class, 'storeService'])->name('services.store');
        Route::get('/services/{service}/edit', [App\Http\Controllers\Provider\ProviderPanelController::class, 'editService'])->name('services.edit');
        Route::patch('/services/{service}', [App\Http\Controllers\Provider\ProviderPanelController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{service}', [App\Http\Controllers\Provider\ProviderPanelController::class, 'destroyService'])->name('services.destroy');
        Route::patch('/services/{service}/toggle-status', [App\Http\Controllers\Provider\ProviderPanelController::class, 'toggleServiceStatus'])->name('services.toggle-status');
        Route::delete('/services/{service}/image/{imageIndex}', [App\Http\Controllers\Provider\ProviderPanelController::class, 'deleteServiceImage'])->name('services.delete-image');
        Route::get('/bookings', [App\Http\Controllers\Provider\ProviderPanelController::class, 'bookings'])->name('bookings');
        Route::get('/reviews', [App\Http\Controllers\Provider\ProviderPanelController::class, 'reviews'])->name('reviews');
        Route::get('/analytics', [App\Http\Controllers\Provider\ProviderPanelController::class, 'analytics'])->name('analytics');
    });
    
    // Admin Panel Routes (Admin Only)
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminPanelController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [App\Http\Controllers\Admin\AdminPanelController::class, 'users'])->name('users');
        Route::patch('/users/{user}/toggle-verification', [App\Http\Controllers\Admin\AdminPanelController::class, 'toggleUserVerification'])->name('users.toggle-verification');
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\AdminPanelController::class, 'toggleUserStatus'])->name('users.toggle-status');
        
        // Category Management
        Route::get('/categories', [App\Http\Controllers\Admin\AdminPanelController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [App\Http\Controllers\Admin\AdminPanelController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Admin\AdminPanelController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\AdminPanelController::class, 'editCategory'])->name('categories.edit');
        Route::patch('/categories/{category}', [App\Http\Controllers\Admin\AdminPanelController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\Admin\AdminPanelController::class, 'destroyCategory'])->name('categories.destroy');
        
        // Service Management
        Route::get('/services', [App\Http\Controllers\Admin\AdminPanelController::class, 'services'])->name('services');
        Route::patch('/services/{service}/toggle-status', [App\Http\Controllers\Admin\AdminPanelController::class, 'toggleServiceStatus'])->name('services.toggle-status');
        Route::patch('/services/{service}/toggle-featured', [App\Http\Controllers\Admin\AdminPanelController::class, 'toggleServiceFeatured'])->name('services.toggle-featured');
        
        // Booking Management
        Route::get('/bookings', [App\Http\Controllers\Admin\AdminPanelController::class, 'bookings'])->name('bookings');
        
        // Review Management
        Route::get('/reviews', [App\Http\Controllers\Admin\AdminPanelController::class, 'reviews'])->name('reviews');
        Route::patch('/reviews/{review}/toggle-approval', [App\Http\Controllers\Admin\AdminPanelController::class, 'toggleReviewApproval'])->name('reviews.toggle-approval');
        
        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Admin\AdminPanelController::class, 'analytics'])->name('analytics');
    });
});

// Public Review Routes
Route::get('/users/{user}/reviews', [App\Http\Controllers\ReviewController::class, 'providerReviews'])->name('reviews.provider');

// Auth routes
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
