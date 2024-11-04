<?php

use App\Http\Controllers\AdminAuthController as AAdminAuth;
use App\Http\Controllers\AuthController as AAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// Group for user-related routes
Route::prefix('usr')->group(function(){
    Route::post('signup', [AAuth::class, 'register']);
    Route::post('signin', [AAuth::class, 'login']);
});

// Group for admin-related routes
Route::prefix('adm')->group(function(){
    Route::post('signup', [AAdminAuth::class, 'register']);
    Route::post('signin', [AAdminAuth::class, 'login']);
});

// Group with authentication middleware
Route::group(['middleware' => ['auth:api', 'TransLog']], function(){

    Route::post('logout', [AAuth::class, 'logout']);

    #################### FILE && GROUP Routes ####################
    Route::post('file/add', [\App\Http\Controllers\FileController::class, 'createFile']);
    Route::post('file/remove', [\App\Http\Controllers\FileController::class, 'removeFile']); 
    Route::post('group/create', [\App\Http\Controllers\GroupController::class, 'createGroup']); 
    Route::post('group/addFile', [\App\Http\Controllers\GroupController::class, 'addFileToGroup']); 
    Route::post('group/removeFile', [\App\Http\Controllers\GroupController::class, 'removeFileFromGroup']); 
    Route::post('group/addUser', [\App\Http\Controllers\GroupController::class, 'addUserToGroup']); 
    Route::post('group/removeUser', [\App\Http\Controllers\GroupController::class, 'removeUserFromGroup']);
    Route::post('group/remove', [\App\Http\Controllers\GroupController::class, 'removeGroup']); 
    Route::post('history/get', [\App\Http\Controllers\HistoryController::class, 'retrieveHistory']); 

    #################### Display Routes ####################
    Route::get('files/mine', [\App\Http\Controllers\DisplayController::class, 'getMyFile']);
    Route::post('file/state', [\App\Http\Controllers\DisplayController::class, 'getStateFile']);
    Route::get('groups/mine', [\App\Http\Controllers\DisplayController::class, 'getMyGroup']);
    Route::post('group/files/get', [\App\Http\Controllers\DisplayController::class, 'getGroupFile']);
    Route::get('users/all', [\App\Http\Controllers\DisplayController::class, 'getAllUserInSystem']);
    Route::post('group/users/all', [\App\Http\Controllers\DisplayController::class, 'getAllUserInGroup']);
    Route::post('group/files/check', [\App\Http\Controllers\DisplayController::class, 'getAllFileCheck_InGroupForUser']);

    #################### Check-in && Check-out ####################
    Route::post('file/checkin', [\App\Http\Controllers\FileOperationsController::class, 'checkIn']); 
    Route::post('file/read', [\App\Http\Controllers\FileOperationsController::class, 'readFile']); 
    Route::post('file/save', [\App\Http\Controllers\FileOperationsController::class, 'saveFile']); 
    Route::post('file/checkout', [\App\Http\Controllers\FileOperationsController::class, 'checkOutFile']);
});

Route::post('file/download', [\App\Http\Controllers\FileOperationsController::class, 'downloadFile'])->middleware('auth:api'); 

#################### Admin Routes ####################
Route::group(['middleware' => ['App\Http\Middleware\AdminAuth:admin-api', 'TransLog']], function(){

    Route::post('admin/logout', [AAdminAuth::class, 'logout']);
    Route::get('admin/files/all', [\App\Http\Controllers\AdminController::class, 'getAllFileInSystem']);
    Route::post('admin/group/files', [\App\Http\Controllers\AdminController::class, 'getAllFileInGroup']);
    Route::get('admin/groups/all', [\App\Http\Controllers\AdminController::class, 'getAllGroupInSystem']);
    Route::post('admin/file/changeNumber', [\App\Http\Controllers\AdminController::class, 'changeFileNumber']);
});
