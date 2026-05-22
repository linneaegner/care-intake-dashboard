<?php

use App\Http\Controllers\Api\IntakeCaseController;
use Illuminate\Support\Facades\Route;

Route::get('/cases', [IntakeCaseController::class, 'index']);
Route::post('/cases', [IntakeCaseController::class, 'store']);
Route::get('/cases/{intakeCase}', [IntakeCaseController::class, 'show']);
Route::patch('/cases/{intakeCase}/status', [IntakeCaseController::class, 'updateStatus']);
Route::patch('/cases/{intakeCase}/note', [IntakeCaseController::class, 'updateNote']);
