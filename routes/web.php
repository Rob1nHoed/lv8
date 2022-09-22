<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::controller(FileController::class)->group(function () {
    Route::get('/file', 'toUpload')->name('file.upload');
    Route::get('/file/upload', 'toUpload')->name('file.upload');
    Route::post('/file/upload/store', 'store')->name('file.store')->middleware('csrf');

    Route::get('/file/download/{key}', 'toDownload')->name('file.toDownload');
    Route::get('/file/download/get/{file}', 'download')->name('file.download');

    Route::get('/file/edit/{file}', 'toEdit')->name('file.toEdit');
    Route::post('/file/edit/{file}/update', 'update')->name('file.update')->middleware('csrf');

    Route::get('/file/delete/{file}', 'toDelete')->name('file.toDelete');
    Route::get('/file/delete/destroy/{file}', 'delete')->name('file.delete');

    Route::get('/file/removeFrom/sended/{file}', 'removeFromSended')->name('file.removeFrom.sended');
});

Auth::routes();


