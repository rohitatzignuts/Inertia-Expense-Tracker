<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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

    // users routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/all', [UserController::class, 'getUsernames'])->name('users.all');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // transactions routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions');
        Route::post('/store', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/{transaction}/update', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    });

    Route::get('{user}/transactions', [TransactionController::class, 'userTransactions'])->name('user.transactions');
});

require __DIR__ . '/auth.php';
