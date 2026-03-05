<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    // Register
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Auth Routes
    Route::middleware('auth:api')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });

});


/*
|--------------------------------------------------------------------------
| PUBLIC JOB ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/jobs/meta/filters', [JobController::class, 'filters']);

Route::get('/jobs', [JobController::class, 'index']);

Route::get('/jobs/{job}', [JobController::class, 'show']);


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/categories',              [CategoryController::class, 'store']);
    Route::put('/categories/{category}',    [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);    
    Route::post('/jobs',              [JobController::class, 'store']);
    Route::put('/jobs/{job}',         [JobController::class, 'update']);
    Route::delete('/jobs/{job}',      [JobController::class, 'destroy']);
    Route::get('/applications',       [ApplicationController::class, 'index']);
    Route::put('/applications/{application}', [ApplicationController::class, 'updateStatus']);
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/my-applications', [ApplicationController::class, 'myApplications']);

});