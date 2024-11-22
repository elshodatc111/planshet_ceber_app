<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::delete('/home_del/{id}', [App\Http\Controllers\HomeController::class, 'home_del'])->name('home_del');
Route::get('/home_show/{id}', [App\Http\Controllers\HomeController::class, 'home_show'])->name('home_show');
Route::get('/home_create', [App\Http\Controllers\HomeController::class, 'home_create'])->name('home_create');
Route::post('/home_create_post', [App\Http\Controllers\HomeController::class, 'home_create_post'])->name('home_create_post');
Route::post('/update_post', [App\Http\Controllers\HomeController::class, 'update_post'])->name('update_post');


Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users');
Route::post('/users_create', [App\Http\Controllers\HomeController::class, 'users_create'])->name('users_create');
Route::delete('/user_del/{id}', [App\Http\Controllers\HomeController::class, 'user_del'])->name('user_del');

Route::post('/update_password', [App\Http\Controllers\HomeController::class, 'update_password'])->name('update_password');

Route::get('/profel', [App\Http\Controllers\HomeController::class, 'profel'])->name('profel');
