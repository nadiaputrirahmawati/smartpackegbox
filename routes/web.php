<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PusherBeamsController;
use App\Http\Controllers\User\ActivityLogController as UserActivityLogController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\SetupController;
use App\Http\Controllers\User\UserController as UserUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

// Route Registrasi Penghuni
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
// Route Auth (Login)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::get('/activity-log', [AdminActivityLogController::class, 'index'])->name('admin.activity-log');
    Route::get('/activity-logs/export-pdf', [AdminActivityLogController::class, 'exportPdf'])->name('activity.export-pdf');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/setup-security', [SetupController::class, 'index'])->name('setup.index');
    Route::post('/setup-security', [SetupController::class, 'store'])->name('setup.store');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/packages', PackageController::class);
    Route::post('/packages/{id}/open-slot', [PackageController::class, 'openSlot'])->name('packages.open_slot');
    Route::get('packages/{id}/session-status', [PackageController::class, 'sessionStatus']);
    Route::post('packages/{id}/extend-session', [PackageController::class, 'extendSession']);
    Route::post('packages/{id}/terminate-session', [PackageController::class, 'terminateSession']);
    Route::get('/packages/{id}/arrive', [PackageController::class, 'showReceiptDetail'])->name('packages.arrive');
    Route::post('/packages/{package}/confirm-money', [PackageController::class, 'confirmMoneyDeposit']);

    Route::get('history/log', [UserActivityLogController::class, 'index'])->name('packages.history');
    Route::post('/box/unlock-door', [UserDashboardController::class, 'openUserDoor']);
    Route::get('/setting/pin', [SettingController::class, 'showPinSecurity'])->name('setting.pin');
    Route::post('/setting/pin/reset', [SettingController::class, 'updatePin'])->name('settings.pin.reset');
    Route::get('setting/profile', [UserUserController::class, 'index'])->name('setting.profile');
    Route::post('/setting/profile/avatar', [UserUserController::class, 'updateAvatar'])->name('setting.profile.avatar');
    Route::post('/setting/profile/password', [UserUserController::class, 'updatePassword'])->name('setting.avatar.password');
    Route::post('/setting/profile', [UserUserController::class, 'updateProfile'])->name('setting.profile');
});

Route::post('/uploadLog', [ActivityLogController::class, 'uploadLog']);

Route::middleware('auth')->group(function () {
    Route::get('/pusher/beams-auth', [PusherBeamsController::class, 'auth']);
});
