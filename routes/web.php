<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\RegistrationController;

Route::get('/', [LandingController::class, 'showHomePage']);
Route::post('/select-role-country', [LandingController::class, 'redirect'])->name('role-country.redirect');

Route::prefix('admin')->group(function () {
    Route::get('/{country}', [FormBuilderController::class, 'show'])->name('admin.form.show');
    Route::post('/{country}', [FormBuilderController::class, 'store']);
    Route::get('/{country}/field/{field}/edit', [FormBuilderController::class, 'edit']);
    Route::put('/{country}/field/{field}', [FormBuilderController::class, 'update']);
    Route::delete('/{country}/field/{field}', [FormBuilderController::class, 'destroy']);
});

Route::get('/register/{country}', [RegistrationController::class, 'showForm']);
Route::post('/register/{country}', [RegistrationController::class, 'store']);
Route::put('/register/{country}/edit/{submission}', [RegistrationController::class, 'update']);
