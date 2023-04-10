<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\UsersController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('authenticate')->controller(UsersController::class)->group(function() {
    Route::post('/', 'login');
});

Route::prefix('users')->controller(UsersController::class)->group(function() {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'store');
    Route::delete('/{id}', 'destroy');
});
