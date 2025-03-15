<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BulletinBoardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('bulletinboard')->group(function () {
    Route::get('/', [BulletinBoardController::class, 'index'])->name('bulletinboard.index');
    Route::get('/posts', [BulletinBoardController::class, 'getPosts'])->name('bulletinboard.getPosts');
    Route::post('/posts', [BulletinBoardController::class, 'store'])->name('bulletinboard.store');
    Route::get('/posts/{post}', [BulletinBoardController::class, 'show'])->name('bulletinboard.show');
    Route::delete('/posts/{post}', [BulletinBoardController::class, 'destroy'])->name('bulletinboard.destroy');

    // いいね機能のCSRFを無効化
    Route::post('/posts/{post}/like', [BulletinBoardController::class, 'likePost'])->name('bulletinboard.likePost')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

    Route::post('/posts/{post}/comments', [BulletinBoardController::class, 'storeComment'])->name('bulletinboard.storeComment');
    Route::post('/posts/{post}/react', [BulletinBoardController::class, 'storeReaction'])->name('bulletinboard.storeReaction');
});


Route::get('/bulletinboard/create', function () {
    return view('bulletinboard.create');
})->name('bulletinboard.create');

require __DIR__ . '/auth.php';
