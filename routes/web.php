<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\PostController;
use App\Http\Controllers\Front\LandingController;
use App\Http\Controllers\Back\PostController as BackPostController;

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

Route::get('/', LandingController::class)->name('landing');


require __DIR__.'/auth.php';


Route::name('front.')
    ->group(function () {
        Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
    });


Route::name('back.')
    ->middleware(["auth"])
    ->prefix('back')
    ->group(function () {
        Route::resource('posts', BackPostController::class)->except(['edit', 'update', 'destroy']);
    });