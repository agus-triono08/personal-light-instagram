<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Halaman utama setelah login
Route::get('home', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk halaman home yang menampilkan semua feed
Route::get('/home', [FeedController::class, 'index'])->name('home');
// Route untuk halaman form upload feed
Route::get('/feed/create', [FeedController::class, 'create'])->name('feed.create');
// Route untuk menyimpan feed ke database
Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');

Route::post('/feeds/{feed}/like', [LikeController::class, 'like']);
Route::post('/feeds/{feed}/unlike', [LikeController::class, 'unlike']);
Route::post('/feeds/{feed}/comment', [CommentController::class, 'store'])->middleware('auth');

Route::post('/feeds/{feed}/like', [FeedController::class, 'like'])->name('feed.like');

Route::get('/archive', [FeedController::class, 'archive'])->name('archive');
Route::post('/archive/download', [FeedController::class, 'download'])->name('archive.download');
