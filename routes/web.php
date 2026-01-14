<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    } else {
        return redirect()->route('login');
    }
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - Require authentication
Route::middleware('auth')->group(function () {
    // User Management Routes
    Route::resource('users', UserController::class);
    Route::get('users/{id}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('users/{id}/update-roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    
    // Role Management Routes
    Route::resource('roles', RoleController::class);
    
    // Permission Management Routes
    Route::resource('permissions', PermissionController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
