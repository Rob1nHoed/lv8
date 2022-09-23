<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
use App\Http\Resources\UserResource;
use App\Http\Resources\FileResource;
use App\Models\User;
use App\Models\File;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::controller(FileController::class)->group(function () {
    Route::get('/file/download/{key}', 'toDownload')->name('file.toDownload');
    Route::get('/file/download/get/{file}', 'download')->name('file.download');
 
    //middelware auth group
    Route::middleware('auth')->group(function () {
        Route::get('/file', 'toUpload')->name('file.upload');
        Route::get('/file/upload', 'toUpload')->name('file.upload');
        Route::post('/file/upload/store', 'store')->name('file.store');
    
        Route::get('/file/edit/{file}', 'toEdit')->name('file.toEdit');
        Route::post('/file/edit/{file}/update', 'update')->name('file.update');
    
        Route::get('/file/delete/{file}', 'toDelete')->name('file.toDelete');
        Route::get('/file/delete/destroy/{file}', 'delete')->name('file.delete');
    
        Route::get('/file/removeFrom/sended/{file}', 'removeFromSended')->name('file.removeFrom.sended');
    });
});

Route::get('/users', function () {
    return UserResource::collection(User::all());
});

Route::get('/user/{id}', function ($id) {
    return new UserResource(User::findOrFail($id));
});

Route::get('/files', function () {
    return FileResource::collection(File::all());
});

Route::get('/file', function () {
    //count the number of files
    $count = File::count();
    //get a random file
    $file = File::inRandomOrder()->first();
    $file = new FileResource($file);
    $file = json_encode($file);
    return $file;
});


Auth::routes();