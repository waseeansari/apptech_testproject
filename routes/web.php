<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    //return view('welcome');
    return view('auth.login');
});

Auth::routes();
//logged in
Route::middleware(['auth'])->group(function () {
    Route::resource('notes', App\Http\Controllers\NoteController::class);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
