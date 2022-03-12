<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectBoardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectLogController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\UserLogController;
use App\Models\ProjectLog;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', 'login');    
            Route::get('logs', 'logs');
            Route::post('store', 'store');        
            Route::post('update', 'update');
            Route::post('logout', 'logout');
            Route::post('update-password', 'updatePassword');
            Route::post('me', 'me');
        });           
    
        Route::post('upload-profile', 'profileImg');
        Route::post('remove-profile', 'deleteProfileImg');
    });
    
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::apiResource('project-member', ProjectMemberController::class);
    Route::apiResource('project', ProjectController::class);
    
    Route::post('board/update-order', [ProjectBoardController::class, 'updateOrder']);
    Route::post('board/update-name', [ProjectBoardController::class, 'updateName']);
    Route::apiResource('board', ProjectBoardController::class);
    
    Route::delete('task/assignee/{id}', [ProjectTaskController::class, 'removeTaskAssignee']);
    Route::post('task/assignee', [ProjectTaskController::class, 'updateTaskAssignee']);
    Route::post('task/update-order', [ProjectTaskController::class, 'updateOrder']);
    Route::apiResource('task', ProjectTaskController::class);
    
    Route::apiResource('task-comment', TaskCommentController::class);
    
    Route::get('account-logs', [UserLogController::class, 'index']);

    Route::post('project-activity/{id}', [ProjectLogController::class, 'getProjectActivity']);
    Route::get('project-logs', [ProjectLogController::class, 'index']);
});