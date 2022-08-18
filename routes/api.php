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
use App\Http\Controllers\Api\StatusController;

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


////login and register for adminController
Route::group(['prefix' => 'admin'], function ($router) {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
});

Route::group(['middleware' => ['jwt.role:0', 'jwt.auth'], 'prefix' => 'user'], function ($router) {

    Route::put('/signinouts', [SignInOutController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/userprofile', [AdminController::class, 'userProfile']);

    //route to Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/projects_delete/{id}', [ProjectController::class, 'destroy']);
    Route::post('/projects_update/{id}', [ProjectController::class, 'update']);
    Route::get('/projectsbyuser', [ProjectController::class, 'getprojectbyuser_id']);

    //route to sign in out
    Route::post('/signinouts_delete/{id}', [SignInOutController::class, 'destroy']);
    Route::get('/signinouts', [SignInOutController::class, 'index']);
    Route::post('/signinouts_admin', [SignInOutController::class, 'update']);
});

////login and register for userController
Route::group(['prefix' => 'user'], function ($router) {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/getstatus', [PostController::class, 'getStatus']);
    Route::get('/getpriority', [PostController::class, 'getPriority']);
});

Route::group(['middleware' => ['jwt.role:1', 'jwt.auth'], 'prefix' => 'user'], function ($router) {

    Route::get('/getuserdetails', [UserController::class, 'getUserDetails']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/userprofile', [UserController::class, 'userProfile']);
    // route to sign in out
    Route::post('/signinouts', [SignInOutController::class, 'store']);
    Route::post('/getsigninouts', [SignInOutController::class, 'getsignioutbyuserid']);
    Route::get('/signinouts', [SignInOutController::class, 'index']);
    Route::post('/signinouts_update', [SignInOutController::class, 'update']);
    Route::get('/signinoutsdetails', [SignInOutController::class, 'getusersigndetails']);//get user detail of current day
    // route to leave
    Route::post('/userleaves', [UserLeaveController::class, 'store']);
    Route::post('/userleaves_delete/{id}', [UserLeaveController::class, 'destroy']);
    Route::get('/userleaves/{id}', [UserLeaveController::class, 'userleaveprofile']);
    Route::get('/userleaves', [UserLeaveController::class, 'index']);
    Route::post('/userleaves_update/{id}', [UserLeaveController::class, 'update']);
    // route to Tasks
    Route::post('/tasksget', [PostController::class, 'index']); //per month and year
    Route::post('/tasks', [PostController::class, 'store']);
    Route::post('/tasks_delete/{id}', [PostController::class, 'destroy']);
    Route::post('/tasks_update/{id}', [PostController::class, 'update']);
});

