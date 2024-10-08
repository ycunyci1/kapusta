<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserSwitchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('check-code', [AuthController::class, 'checkCode']);
    Route::post('login', [AuthController::class, 'login']);
//    Route::middleware('auth:api')->group(function () {

    Route::get('projects/history', HistoryController::class);

    Route::get('projects/{projectId}/expenses', [ExpenseController::class, 'index']);
    Route::post('projects/{projectId}/expenses', [ExpenseController::class, 'store']);
    Route::delete('projects/{projectId}/expenses/{expenseId}', [ExpenseController::class, 'destroy']);
    Route::resource('projects', ProjectController::class);


    Route::delete('expenses/{expenseId}', [ExpenseController::class, 'destroy']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('accounts', [AccountController::class, 'index']);


    //свитч пользователя
    Route::get('/switch-random', [UserSwitchController::class, 'switchToRandomUser']);
    Route::get('/switch-new', [UserSwitchController::class, 'switchToNewUser']);

//    });
});
