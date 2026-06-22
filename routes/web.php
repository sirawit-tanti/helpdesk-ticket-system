<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\ReportController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/tickets/my', [TicketController::class, 'myTickets'])->name('tickets.my');
    Route::get('/tickets/assigned-to-me', [TicketController::class, 'assignedToMe'])->name('tickets.assigned-to-me');
    Route::get('/tickets/unassigned', [TicketController::class, 'unassigned'])->name('tickets.unassigned');
    Route::patch('/tickets/bulk-action', [TicketController::class, 'bulkAction'])->name('tickets.bulk-action');
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');
    Route::patch('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::patch('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
    Route::patch('/tickets/{ticket}/assign-to-me', [TicketController::class, 'assignToMe'])->name('tickets.assign-to-me');
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::patch('/tickets/{ticket}/unassign', [TicketController::class, 'unassign'])->name('tickets.unassign');
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