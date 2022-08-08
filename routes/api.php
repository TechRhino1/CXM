<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserLeaveController;
use App\Http\Controllers\Api\SignInOutController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [PostController::class, 'index']);
});


// route to Tasks
Route::get('/tasks', [PostController::class, 'index']);
Route::post('/tasks', [PostController::class, 'store']);
Route::delete('/tasks/{id}', [PostController::class, 'destroy']);
Route::put('/tasks/{id}', [PostController::class, 'update']);

//route to Projects
Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
Route::put('/projects/{id}', [ProjectController::class, 'update']); 

//route to user leaves
Route::get('/userleaves/{id}', [UserLeaveController::class, 'userleaveprofile']);
Route::get('/userleaves', [UserLeaveController::class, 'index']);
Route::post('/userleaves', [UserLeaveController::class, 'store']);
Route::delete('/userleaves/{id}', [UserLeaveController::class, 'destroy']);

//route to sign in out
Route::delete('/signinouts/{id}', [SignInOutController::class, 'destroy']);


////login and register for adminController
Route::group(['prefix' => 'admin'], function ($router) {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
});

Route::group(['middleware' => ['jwt.role:0','jwt.auth'],'prefix' => 'user'], function($router){
    
    Route::put('/signinouts/{id}', [SignInOutController::class, 'update']);
    Route::get('/signinouts', [SignInOutController::class, 'index']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/userprofile', [AdminController::class, 'userProfile']);
} );

////login and register for userController
Route::group(['prefix' => 'user'], function ($router) {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});

Route::group(['middleware' => ['jwt.role:1','jwt.auth'],'prefix' => 'user'], function($router){
    
    Route::put('/userleaves', [UserLeaveController::class, 'update']);
    Route::post('/signinouts', [SignInOutController::class, 'store']);
    
    Route::post('/logout', [UserController::class, 'logout']);


    Route::get('/userprofile', [UserController::class, 'userProfile']);

} );

