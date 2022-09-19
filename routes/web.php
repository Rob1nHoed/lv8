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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/file', [FileController::class, 'toUpload'])->name('file.upload');
Route::get('/file/upload', [FileController::class, 'toUpload'])->name('file.upload');
Route::post('/file/upload/store', [FileController::class, 'store'])->name('file.store');

Route::get('/file/download/{key}', [FileController::class, 'toDownload'])->name('file.toDownload');
Route::get('/file/download/get/{file}', [FileController::class, 'download'])->name('file.download');

Route::get('/file/edit/{key}', [FileController::class, 'toEdit'])->name('file.toEdit');
Route::post('/file/edit/update/{key}', [FileController::class, 'update'])->name('file.update');

Route::get('/file/delete/{key}', [FileController::class, 'toDelete'])->name('file.toDelete');
Route::get('/file/delete/destroy/{key}', [FileController::class, 'delete'])->name('file.delete');

Auth::routes();


