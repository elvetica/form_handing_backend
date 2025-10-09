<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormSubmissionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\PasswordResetController;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/api', function () {
    return response()->json([
        'message' => 'Form Handling API',
        'version' => '1.0',
        'endpoints' => [
            'POST /api/forms/submit' => 'Submit form data',
            'GET /api/health' => 'Health check',
        ],
        'admin_panel' => url('/admin/login'),
    ]);
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Guest routes (login only)
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1'); // 5 attempts per minute
    });

    // Registration routes (accessible to everyone, controller handles auth)
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])
        ->middleware('throttle:3,10'); // 3 attempts per 10 minutes

    // Password reset routes (accessible to everyone)
    Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])
        ->name('admin.password.email')
        ->middleware('throttle:3,10'); // 3 attempts per 10 minutes
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])
        ->name('admin.password.update')
        ->middleware('throttle:3,10'); // 3 attempts per 10 minutes

    // Email verification routes (must be authenticated but not necessarily verified)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'notice'])->name('admin.verification.notice');
        Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('admin.verification.verify');
        Route::post('/email/resend', [VerificationController::class, 'resend'])
            ->name('admin.verification.resend')
            ->middleware('throttle:3,1'); // 3 attempts per minute
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
            'update',
            'destroy'
        ])->names([
                    'index' => 'admin.admins.index',
                    'show' => 'admin.admins.show',
                    'edit' => 'admin.admins.edit',
                    'update' => 'admin.admins.update',
                    'destroy' => 'admin.admins.destroy',
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

        // Invitations
        Route::get('/invitations', [InvitationController::class, 'index'])->name('admin.invitations.index');
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('admin.invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])
            ->name('admin.invitations.store')
            ->middleware('throttle:10,60'); // 10 invitations per hour
        Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])
            ->name('admin.invitations.destroy');
    });
});
