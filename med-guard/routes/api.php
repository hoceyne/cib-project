<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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


//Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);
Route::post('/forgotpassword', [AuthController::class, 'forgotpassword']);



Route::middleware(['auth:api'])->group(function () {

    Route::get('apitokens/check', function (Request $request) {
        return true;
    });
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('profile/{id}', [AuthController::class, 'profile']);
    Route::post('profile/{id}/password/change', [AuthController::class, 'profile']);


    Route::get('users/{id?}', [AdminController::class, 'users']);
    Route::post('users/create', [AdminController::class, 'create_user']);
    Route::delete('users/{id}/delete', [AdminController::class, 'delete_user']);
    Route::put('users/update', [AdminController::class, 'update_user']);

    Route::get('contact/messages/{id?}', [AdminController::class, 'messages']);
    Route::post('contact/messages/create', [AdminController::class, 'create_message']);
    Route::delete('contact/messages/{id}/respond', [AdminController::class, 'respond']);

    Route::get('notifications/{id?}', [AdminController::class, "notifications"]);
    Route::post('notifications/create', [AdminController::class, 'create_notification']);
    Route::delete('notifications/{id}/delete', [AdminController::class, 'delete_notification']);

    Route::get('posts/{id?}', [AdminController::class, 'users']);
    Route::post('posts/create', [AdminController::class, 'create_user']);
    Route::delete('psots/{id}/delete', [AdminController::class, 'delete_user']);
    Route::put('psots/update', [AdminController::class, 'update_user']);
});
