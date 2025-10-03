<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormSubmissionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect('/api');
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Guest routes (login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Form Submissions CRUD
        Route::resource('form_submissions', FormSubmissionController::class)->only([
            'index',
            'show',
            'edit',
            'update'
        ])->names([
                    'index' => 'admin.form_submissions.index',
                    'show' => 'admin.form_submissions.show',
                    'edit' => 'admin.form_submissions.edit',
                    'update' => 'admin.form_submissions.update',
                ]);

        // Admins CRUD
        Route::resource('admins', AdminController::class)->only([
            'index',
            'show',
            'edit',
            'update'
        ])->names([
                    'index' => 'admin.admins.index',
                    'show' => 'admin.admins.show',
                    'edit' => 'admin.admins.edit',
                    'update' => 'admin.admins.update',
                ]);

        // Users CRUD
        Route::resource('users', UserController::class)->only([
            'index',
            'show',
            'edit',
            'update'
        ])->names([
                    'index' => 'admin.users.index',
                    'show' => 'admin.users.show',
                    'edit' => 'admin.users.edit',
                    'update' => 'admin.users.update',
                ]);
    });
});
