<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\SessionsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/sessions/{sessionId}', [SessionsController::class, 'show']);
Route::patch('/sessions/{sessionId}/end', [SessionsController::class, 'end']);
Route::patch('/sessions/{sessionId}', [SessionsController::class, 'update']);
Route::post('/sessions', [SessionsController::class, 'store']);
Route::get('/sessionstop', [SessionsController::class, 'top']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/questions', QuestionsController::class);
    Route::get('/sessions', [SessionsController::class, 'index']);
});
