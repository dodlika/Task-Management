<?php

use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskExportController;
use App\Http\Controllers\TaskFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default route
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (manually defined)
Route::group(['namespace' => 'Auth'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Scheduler routes
Route::get('/scheduler/check-due-tasks/{token}', [SchedulerController::class, 'checkDueTasks'])
    ->name('scheduler.check-due-tasks')
    ->middleware('throttle:10,1');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');
    
    // Task Management
    Route::post('/tasks/{task}/toggle-tracking', [TaskController::class, 'toggleTracking'])
    ->name('tasks.toggle-tracking');
    Route::get('/tasks/calendar', [TaskController::class, 'calendar'])->name('tasks.calendar');
    
    // Task Exports
    Route::get('/tasks/export/all', [TaskExportController::class, 'exportAll'])->name('tasks.export.all');
    Route::get('/tasks/{task}/export', [TaskExportController::class, 'exportTask'])->name('tasks.export');
    
    // Task Files
    Route::post('/tasks/{task}/files', [TaskFileController::class, 'store'])->name('tasks.files.store');
    Route::delete('/tasks/{task}/files/{file}', [TaskFileController::class, 'destroy'])->name('tasks.files.destroy');
    Route::get('/tasks/{task}/files/{file}/download', [TaskFileController::class, 'download'])->name('tasks.files.download');
    
    // Tasks Resource Routes
    Route::resource('tasks', TaskController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
    });
}); 
