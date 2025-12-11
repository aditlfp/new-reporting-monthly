<?php

use App\Http\Controllers\Admin\DataFotoController;
use App\Http\Controllers\API\CalenderApiHandler;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoverReportControllers;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixedImageController;
use App\Http\Controllers\ReportLettersControllers;
use App\Http\Controllers\SendImageStatusController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\UserNavigateController;
use App\Http\Controllers\UserSettingsController;
use App\Models\Clients;
use App\Models\Kerjasama;
use App\Models\UploadImage;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'theme'])->name('dashboard');

Route::middleware(['auth', 'theme'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/upload-img-lap', UploadImageController::class);
    Route::post('/upload-img-lap-draft', [UploadImageController::class, 'draft'])->name('upload-images.draft');
    Route::get('/send-img/laporan', [UserNavigateController::class, 'toUploadImgLaporan'])->name('send.img.laporan');
    Route::get('/performance-per-month', [DashboardController::class, 'performancePerMonth']);

    Route::resource('/set-image/fixed', FixedImageController::class)->only('index', 'create', 'store');

    Route::get('/api/v1/count-data', [UploadImageController::class, 'countData'])->name('v1.count.data');

    // Tools Route
    Route::get('/check-calender', [UserNavigateController::class, 'toCalenderUpload'])->name('check.calender.upload');
    Route::get('/fetch-calender', [CalenderApiHandler::class, 'getCalendarData']);
    Route::get('/settings', [UserNavigateController::class, 'toSettings'])->name('user.settings.index');
    Route::post('/save-settings', [UserSettingsController::class, 'store']);
    // End Tools Route
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('/admin-covers', CoverReportControllers::class);
    Route::post('admin-covers/store-pdf', [CoverReportControllers::class, 'store_pdf']);
    Route::resource('/admin-latters', ReportLettersControllers::class);
    Route::get('/admin-check-status', [SendImageStatusController::class, 'index'])->name('check.upload');
    Route::get('/admin-check-status/{admin_check_status}/{month}', [SendImageStatusController::class, 'show'])->name('check.upload.show');

    Route::get('admin/upload/get-pdf-data', [UploadImageController::class, 'getPdfData'])->name('admin.upload.get-pdf-data');
    Route::post('admin/upload/store-pdf', [UploadImageController::class, 'storePdf'])->name('admin.upload.store-pdf');
    Route::get('/admin-settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin-settings', [SettingsController::class, 'store'])->name('admin.set.settings');

    Route::get('/admin-foto-progress', [DataFotoController::class, 'index'])->name('admin.upload.index');
    Route::put('/admin-foto-progress-update/{upload_image}', [DataFotoController::class, 'update'])->name('admin.upload.update');
    Route::delete('/admin-foto-progress-delete/{upload_image}', [DataFotoController::class, 'destroy'])->name('admin.upload.destroy');
});

Route::view('/testPage', 'pages.admin.tesPages');


require __DIR__.'/auth.php';
