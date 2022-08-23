<?php



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

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



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

//     return $request->user();

// });





////login and register for adminController

Route::group(['prefix' => 'admin'], function ($router) {

    Route::post('/login', [AdminController::class, 'login']);

    Route::post('/register', [AdminController::class, 'register']);

});



Route::group(['middleware' => ['jwt.role:0', 'jwt.auth'], 'prefix' => 'user'], function ($router) {



    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/getuserbyid', [UserController::class, 'getuserbyid']);

    Route::post('/getusersdata', [SignInOutController::class, 'getusersdata']);

    Route::post('/registeradmin', [SignInOutController::class, 'register']); ///ttttttaddd




    //route to Projects


    Route::post('/projects', [ProjectController::class, 'store']);

    Route::post('/projects_delete/{id}', [ProjectController::class, 'destroy']);

    Route::post('/projects_update/{id}', [ProjectController::class, 'update']);




    //route to sign in out

    Route::post('/signinouts_delete/{id}', [SignInOutController::class, 'destroy']);

    Route::post('/signinouts_admin', [SignInOutController::class, 'update']);

});



////login and register for userController

Route::group(['prefix' => 'user'], function ($router) {

    Route::post('/login', [UserController::class, 'login']);

    Route::post('/register', [UserController::class, 'register']); //register for user

    Route::get('/getstatus', [PostController::class, 'getStatus']); //get status

    Route::get('/getpriority', [PostController::class, 'getPriority']);   //get priority

});



Route::group(['middleware' => ['jwt.role:1,0', 'jwt.auth'], 'prefix' => 'user'], function ($router) {



    Route::get('/getuserdetails', [UserController::class, 'getUserDetails']);

    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/userprofile', [UserController::class, 'userProfile']);

    // route to sign in out

    Route::post('/signinouts', [SignInOutController::class, 'store']);

    Route::post('/getsigninouts', [SignInOutController::class, 'getsignioutbyuserid']); //get sign in out by user id in given month and year

    Route::get('/signinouts', [SignInOutController::class, 'index']);

    Route::post('/signinouts_update', [SignInOutController::class, 'update']);

    Route::get('/signinoutsdetails', [SignInOutController::class, 'getusersigndetails']);//get user detail of current day

    Route::post('/getcurrentsigninout', [SignInOutController::class, 'getcurrentsigninout']);//get user detail of user today with userid and date
    // route to leave

    Route::post('/userleaves', [UserLeaveController::class, 'store']);

    Route::post('/userleaves_delete/{id}', [UserLeaveController::class, 'destroy']);

    Route::get('/userleaves/{id}', [UserLeaveController::class, 'userleaveprofile']);

    Route::get('/userleaves', [UserLeaveController::class, 'index']);

    Route::post('/userleaves_update/{id}', [UserLeaveController::class, 'update']);

    Route::post('/getleavebyid', [UserLeaveController::class, 'getleavebyleaveid']); //get leave by leave id

    // route to Tasks

    Route::post('/gettasks', [PostController::class, 'index']); //per month and year

    Route::post('/tasks', [PostController::class, 'store']);

    Route::post('/tasks_delete/{id}', [PostController::class, 'destroy']);

    Route::post('/tasks_update/{id}', [PostController::class, 'update']);

    Route::post('/getUncompletedTasks', [PostController::class, 'getUncompletedTasks']); //get all uncompleted tasks of current user

    // route to projects
    Route::get('/projects', [ProjectController::class, 'index']); //get all projects
    Route::get('/myprojectstatus', [ProjectController::class, 'getmyprojectstatus']);//get user detail of project status
    Route::post('/saveprojectuser', [ProjectController::class, 'saveprojectofuser']); ///get project of user customize
    Route::post('/projectsbyuser', [ProjectController::class, 'getprojectbyuser_id']); //get project by user id
    Route::post('/gettaskdetails', [PostController::class, 'gettaskdetails']);

});





