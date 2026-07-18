<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    Route::resource('/user', UserController::class)->middleware('role:superadmin');
    Route::resource('/organization', OrganizationController::class)->middleware('role:superadmin,admin');
    Route::resource('/category', CategoryController::class)->middleware('role:superadmin');
    
    Route::resource('/event', EventController::class)->middleware('role:superadmin,admin');
    Route::post('/event/{event}/speaker', [SpeakerController::class, 'store'])->name('speaker.store')->middleware('role:superadmin,admin');
    Route::put('/event/{event}/speaker/{speaker}', [SpeakerController::class, 'update'])->name('speaker.update')->middleware('role:superadmin,admin');
    Route::delete('/event/{event}/speaker/{speaker}', [SpeakerController::class, 'destroy'])->name('speaker.destroy')->middleware('role:superadmin,admin');

    Route::post('/event/{event}/schedule', [EventScheduleController::class, 'store'])->name('schedule.store')->middleware('role:superadmin,admin');
    Route::put('/event/{event}/schedule/{schedule}', [EventScheduleController::class, 'update'])->name('schedule.update')->middleware('role:superadmin,admin');
    Route::delete('/event/{event}/schedule/{schedule}', [EventScheduleController::class, 'destroy'])->name('schedule.destroy')->middleware('role:superadmin,admin');

    Route::post('/event/{event}/sponsor', [SponsorController::class, 'store'])->name('sponsor.store')->middleware('role:superadmin,admin');
    Route::put('/event/{event}/sponsor/{sponsor}', [SponsorController::class, 'update'])->name('sponsor.update')->middleware('role:superadmin,admin');
    Route::delete('/event/{event}/sponsor/{sponsor}', [SponsorController::class, 'destroy'])->name('sponsor.destroy')->middleware('role:superadmin,admin');

    Route::post('/event/{event}/register', [App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');
    Route::put('/event/{event}/registration/{registration}/checkin', [App\Http\Controllers\RegistrationController::class, 'checkIn'])->name('registration.checkin')->middleware('role:superadmin,admin');

    Route::post('/event/{event}/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    Route::put('/notification/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notification.read');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');

    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
        Route::get('/report/export-all', [App\Http\Controllers\ReportController::class, 'exportAll'])->name('report.exportAll');
        Route::get('/report/export/{event}', [App\Http\Controllers\ReportController::class, 'exportEvent'])->name('report.exportEvent');
    });
});
