<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



/**
 *  Sin autenticación
 */

Route::prefix('auth')->group( function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('auth')->group( function () {
    Route::post('register', [AuthController::class, 'register']);
});

/**
 *  Con autenticación
 */

Route::middleware('auth:sanctum')->group( function () {

});

