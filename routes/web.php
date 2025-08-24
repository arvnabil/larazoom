<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\ZoomOAuthController;
use App\Models\Meeting; // Penting
Route::get('/', function () {
    return view('welcome');
});

Route::get('/zoom/redirect', [ZoomOAuthController::class, 'redirect'])->name('zoom.redirect');
Route::get('/zoom/callback', [ZoomOAuthController::class, 'callback'])->name('zoom.callback');

// Route untuk menampilkan halaman meeting
Route::get('/meetings/{meeting}', [ZoomMeetingController::class, 'show'])->name('meetings.show')->middleware('auth');
// Route-nya bisa seperti ini
Route::get('/meetings/{meeting}/join', [ZoomMeetingController::class, 'show'])
    ->middleware('auth')
    ->name('meetings.join');
