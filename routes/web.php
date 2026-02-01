<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AuditLog\AuditLogController;
use App\Http\Controllers\LogViewer\LogViewerController;
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
    
    // Audit Log Routes
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    
    // Log Viewer Routes
    Route::get('log-viewer', [LogViewerController::class, 'index'])->name('log-viewer.index');
    Route::get('log-viewer/{file}/show/{line}', [LogViewerController::class, 'show'])->name('log-viewer.show');
    Route::get('log-viewer/{file}/download', [LogViewerController::class, 'download'])->name('log-viewer.download');
    Route::get('log-viewer/{file}/delete', [LogViewerController::class, 'delete'])->name('log-viewer.delete');
    
    // Email Management Routes
    Route::resource('email-types', \App\Http\Controllers\EmailType\EmailTypeController::class);
    Route::resource('email-templates', \App\Http\Controllers\EmailTemplate\EmailTemplateController::class);
    Route::post('email-templates/{id}/test-send', [\App\Http\Controllers\EmailTemplate\EmailTemplateController::class, 'testSend'])->name('email-templates.test-send');
    Route::get('email-logs', [\App\Http\Controllers\EmailLog\EmailLogController::class, 'index'])->name('email-logs.index');
    Route::get('email-logs/{id}', [\App\Http\Controllers\EmailLog\EmailLogController::class, 'show'])->name('email-logs.show');
});