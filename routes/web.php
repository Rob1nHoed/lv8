<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;

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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/file/download/{key}', [FileController::class, 'toDownload']);

Route::get('/file', [FileController::class, 'toUpload'])->name('file.upload');

Route::get('/file/upload', [FileController::class, 'toUpload'])->name('file.upload');

Route::post('/file/upload/store', [FileController::class, 'store'])->name('file.store');

Route::get('/file/download/get/{file}', [FileController::class, 'download'])->name('file.download');


Auth::routes();


