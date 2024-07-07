<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/', [MainController::class, 'index'])->middleware('check.role');
    Route::get('/sample', [MainController::class, 'getSample']);
    Route::get('/sample/status', [MainController::class, 'getSampleChangeStatus']);
    Route::get('/sample/change', [MainController::class, 'getBackupSample']);
    Route::get('/sample/full', [MainController::class, 'getSampleFull']);
    Route::patch('/sample/change', [MainController::class, 'changeSample']);
    Route::delete('/sample/change', [MainController::class, 'deleteChangeSample']);
    Route::patch('/sample/notactive/{id}', [MainController::class, 'setNotActive']);

    Route::get('/my-sample', [MainController::class, 'getMySample']);
    Route::get('/status', [PetugasController::class, 'status']);

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/admin', [AdminController::class, 'index']);
    });

    Route::group(['middleware' => ['role:pml']], function () {
        Route::get('/recommendation', [PetugasController::class, 'recommendation']);
    });
});

require __DIR__ . '/auth.php';
