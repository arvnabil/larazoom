<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\MyMeetingsController;
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\ZoomOAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/zoom/redirect', [ZoomOAuthController::class, 'redirect'])->name('zoom.redirect');
Route::get('/zoom/callback', [ZoomOAuthController::class, 'callback'])->name('zoom.callback');
// Route untuk bergabung ke halaman meeting (untuk guru dan siswa)
Route::get('/meetings/{meeting}/join', [ZoomMeetingController::class, 'show'])
    ->middleware('auth')
    ->name('meetings.join');

// Halaman untuk menampilkan daftar meeting milik user (guru/siswa)
Route::get('/my-meetings', [MyMeetingsController::class, 'index'])
    ->middleware('auth')
    ->name('my-meetings.index');
