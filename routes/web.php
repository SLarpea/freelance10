<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::post('setLocale', [\App\Http\Controllers\LocalizationController::class, 'setLocale'])->name('set.locale');

    Route::middleware('user_has_role')->group(function () {
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\RolesController::class, 'index'])->name('index');
            Route::post('/create', [\App\Http\Controllers\RolesController::class, 'create'])->name('create');
            Route::post('/update', [\App\Http\Controllers\RolesController::class, 'update'])->name('update');
            Route::post('/delete', [\App\Http\Controllers\RolesController::class, 'remove'])->name('remove');
            Route::post('/change-status', [\App\Http\Controllers\RolesController::class, 'changeStatus'])->name('change-status');
        });
    
        Route::prefix('user-roles')->name('user-roles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\UserRoleController::class, 'index'])->name('index');
            Route::post('/assign', [\App\Http\Controllers\UserRoleController::class, 'assignRole'])->name('assign');
        });
    
        Route::prefix('shops')->name('shops.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ShopController::class, 'index'])->name('index');
            Route::post('/create', [\App\Http\Controllers\ShopController::class, 'create'])->name('create');
            Route::post('/update', [\App\Http\Controllers\ShopController::class, 'update'])->name('update');
            Route::post('/delete', [\App\Http\Controllers\ShopController::class, 'remove'])->name('remove');
            Route::post('/change-status', [\App\Http\Controllers\ShopController::class, 'changeStatus'])->name('change-status');
        });
    
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ActivityLogsController::class, 'index'])->name('index');
        });
    
        Route::prefix('swagger')->name('swagger.')->group(function () {
            Route::get('/docu', [\App\Http\Controllers\SwaggerController::class, 'index'])->name('docu');
        });
    
        Route::prefix('pulse')->name('pulse.')->group(function () {
            Route::get('/track', [\App\Http\Controllers\PulseConroller::class, 'index'])->name('track');
        });
    });
});

Route::get('admin-lte', function () {
    return Inertia::render('AdminLTE');
})->name('admin.lte');

Route::get('sample-page', function () {
    return Inertia::render('SamplePage');
})->name('sample.page');
