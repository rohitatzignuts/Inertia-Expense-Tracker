<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $loggedUsers = User::where('is_admin', '!=', 1)->get();
    $user = Auth::user();
    return Inertia::render('Dashboard', [
        'loggedUsers' => $loggedUsers,
        'currentUser' => $user,
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
});

require __DIR__ . '/auth.php';
