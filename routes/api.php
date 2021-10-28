<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUpload\FileUploadController;
use App\Http\Controllers\Survey\SurveyController;
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

Route::group(['prefix' => 'v1'], function(){
    Route::post('upload-surveys', [FileUploadController::class, 'uploadSurveys']);
    Route::post('login', [AuthController::class, 'login']);

    Route::get('download-surveys', [FileUploadController::class, 'saveJSONToExcel']);
    Route::get('download-all-surveys', [FileUploadController::class, 'downloadAllFiles']);
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::get('sign-in-with-user', [AuthController::class, 'signInWithUser']);
    Route::get('surveys', [SurveyController::class, 'getAll']);
    Route::post('logout', [AuthController::class, 'logout']);

});
