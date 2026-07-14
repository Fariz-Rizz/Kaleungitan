<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/dashboard', 'user.dashboard')->middleware(['auth'])->name('dashboard');
Route::view('/browse-items', 'user.browse')->middleware(['auth'])->name('browse-items');
Route::view('/report-item', 'user.report')->middleware(['auth'])->name('report-item');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/browse', 'user.browse')->name('browse.items');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/claim', [ItemController::class, 'claim'])->name('items.claim');

    Route::view('/report/lost', 'user.report-lost')->name('report.lost');
    Route::view('/report/found', 'user.report-found')->name('report.found');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/items/{item}', [AdminItemController::class, 'show'])->name('admin.items.show');
    Route::view('/admin/reports', 'admin.reports')->name('admin.reports');
    Route::view('/admin/claims', 'admin.claims')->name('admin.claims');
    Route::view('/admin/categories', 'admin.categories')->name('admin.categories');
    Route::view('/admin/users', 'admin.users')->name('admin.users');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthenticatedSessionController::class, 'store'])->name('admin.login.store');
});

Route::post('/admin/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.logout');

require __DIR__ . '/auth.php';
