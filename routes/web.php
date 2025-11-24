<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoverReportControllers;
use App\Http\Controllers\ReportLettersControllers;
use App\Http\Controllers\UploadImageController;
use App\Models\Clients;
use App\Models\Kerjasama;
use App\Models\UploadImage;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role_id == 2) {
        return view('dashboard');
    } else {
        $uploadDraft = UploadImage::where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->latest()
            ->first();
        return view('pages.user.dashboard', compact('uploadDraft'));
    }
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/upload-img-lap', UploadImageController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('/admin-covers', CoverReportControllers::class);
    Route::resource('/admin-latters', ReportLettersControllers::class);
});


require __DIR__.'/auth.php';
