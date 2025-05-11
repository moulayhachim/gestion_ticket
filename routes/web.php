<?php

use App\Http\Controllers\{
    DashboardController,
    TicketController,
    CommentController,
    UserController,
    AttachmentController,
    ProfileController,
    Auth\RegisteredUserController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('dashboard'));

// Public registration
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Public user listing (optional)
Route::get('/users', [UserController::class, 'list'])->name('users.index');

// Authenticated & verified routes
Route::middleware(['auth', 'verified'])->group(function () {

    // ✅ TICKETS — CRUD
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index'); // List all tickets
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create'); // Show form to create a ticket
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store'); // Store a new ticket
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show'); // Show a specific ticket
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit'); // Show form to edit a ticket
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update'); // Update a specific ticket
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy'); // Delete a specific ticket

    // Additional ticket-related route
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign'); // Assign a ticket

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Attachments
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'list'])->name('users.index'); // List all users
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Show form to create a user
        Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Store a new user
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Show a specific user
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Show form to edit a user
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update a specific user
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete a specific user

        Route::get('/settings', fn() => view('settings.index'))->name('settings.index');
        Route::post('/settings/create-user', [UserController::class, 'createUserFromSettings'])->name('settings.createUser');
    });
});

// Auth routes (Laravel Breeze/Fortify/etc.)
require __DIR__ . '/auth.php';
