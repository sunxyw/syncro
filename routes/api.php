<?php

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

Route::get('public', function () {
    if (Auth::check()) {
        dump(Auth::user());
    } else {
        dump('Not logged in');
    }
})->middleware('auth0.authorize.optional');
