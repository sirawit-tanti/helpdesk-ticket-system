<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\Admin\TicketCategoryController as AdminTicketCategoryController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\TicketPriorityController as AdminTicketPriorityController;
use App\Http\Controllers\Admin\TicketStatusController as AdminTicketStatusController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])
    ->name('tickets.comments.store');

    Route::patch('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])
        ->name('tickets.resolve');

    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])
        ->name('tickets.close');

    Route::patch('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])
        ->name('tickets.reopen');
    
    Route::resource('tickets', TicketController::class);

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('admin')
        ->group(function () {
            Route::resource('categories', AdminTicketCategoryController::class)
                ->except(['show']);

            Route::resource('departments', AdminDepartmentController::class)
                ->except(['show']);

            Route::resource('priorities', AdminTicketPriorityController::class)
                ->except(['show']);
            
            Route::resource('statuses', AdminTicketStatusController::class)
                ->except(['show']);

            Route::resource('users', AdminUserController::class)
                ->except(['show']);
        });
});