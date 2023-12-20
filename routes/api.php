<?php

use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\CandidatureController;
use App\Http\Controllers\AuthController;
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
Route::get('/', function(){
    return response()->json([
        'success' => true,
        'message' => 'Welcome to Simplon manager API',
        'version '   => '1.0',
        'support' => 'mamadoucire99@gmail.com',
    ]);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('refresh', 'refresh');
    Route::post('logout', 'logout');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('admin/formation', FormationController::class);
    Route::apiResource('admin/users', AdminUserController::class);

    Route::post('user/candidater/formation-{formationId}', [CandidatureController::class, 'candidater']);
    Route::put('admin/candidater/{formationId}', [CandidatureController::class, 'accepteUser']);
    
    Route::controller(AdminUserController::class)->group(function () {
        Route::post('admin/users-cadidature', 'index');
        Route::post('admin/users-acepted', 'isAcepted');
        Route::post('admin/users-rejected', 'isNotAcepted');
    });
});


