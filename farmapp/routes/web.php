<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';

// Admin routes — only admin role can access
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
});

// Customer routes — only customer role can access
Route::middleware(['auth', 'role:customer'])->prefix('shop')->name('shop.')->group(function () {
    Route::get('/', fn() => view('shop.index'))->name('index');
});

// Shop/bulk buyer routes — only shop role can access
Route::middleware(['auth', 'role:shop'])->prefix('bulk')->name('bulk.')->group(function () {
    Route::get('/', fn() => view('bulk.index'))->name('index');
});

// Staff routes — only staff role can access
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', fn() => view('staff.index'))->name('index');
});