<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Auth\AdminPasswordResetController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\AnnouncementController;

// --- Public Routes ---
// These routes can be accessed by anyone.
// This file includes routes for login, registration, password reset, etc.
require __DIR__.'/auth.php';

// --- Authenticated User Routes ---
// Users must be logged in to access these routes.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- Admin Routes ---
// Admin Login Routes (Public)
Route::get('/admin', [AdminLoginController::class, 'create']);
Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'store']);
Route::get('admin/forgot-password', [AdminPasswordResetController::class, 'create'])->name('admin.password.request');
Route::post('admin/forgot-password', [AdminPasswordResetController::class, 'store'])->name('admin.password.email');
Route::get('admin/reset-password/{token}', [AdminPasswordResetController::class, 'edit'])->name('admin.password.reset');
Route::post('admin/reset-password', [AdminPasswordResetController::class, 'update'])->name('admin.password.update');
Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');
// Admin Protected Routes
Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserManagementController::class)->except(['show', 'create', 'edit']);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('/user-activity', [UserActivityController::class, 'index'])->name('user_activity.index');
    Route::resource('levels', LevelController::class)->except(['show', 'create', 'edit']);
    Route::resource('tasks', TaskController::class)->except(['show', 'create', 'edit']);
    Route::get('/kyc-submissions', [KycController::class, 'index'])->name('kyc.index');
    Route::patch('/kyc-submissions/{kycSubmission}', [KycController::class, 'update'])->name('kyc.update');
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::patch('/investments/{investmentRequest}', [InvestmentController::class, 'update'])->name('investments.update');
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::patch('/withdrawals/{withdrawalRequest}', [WithdrawalController::class, 'update'])->name('withdrawals.update');
    Route::resource('announcements', AnnouncementController::class)->except(['show', 'create', 'edit']);
});
