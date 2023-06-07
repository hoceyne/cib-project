<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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



Route::middleware(['check-api-token'])->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->attributes->get('auth_user');

        return response()->json($user,200);
        // Your protected route logic here
    });
});
