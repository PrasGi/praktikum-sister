<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DefineController;
use App\Http\Controllers\FinanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::get('/finance', [FinanceController::class, 'index']);
        Route::post('/finance', [FinanceController::class, 'store']);
        Route::put('/finance/{finance}', [FinanceController::class, 'update']);
        Route::delete('/finance/{finance}', [FinanceController::class, 'destroy']);

        Route::get('/finance/total/month', [FinanceController::class, 'countTotalAmountByMounthCurrent']);
        Route::get('/finance/total/all', [FinanceController::class, 'countTotalAmountAll']);
        Route::get('/finance/data/chart', [FinanceController::class, 'chartFinance']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/not/auth', [DefineController::class, 'authNot'])->name('auth.not');