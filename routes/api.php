<?php

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

/**
 * API
 */
Route::post('add', [\App\Http\Controllers\DownloaderController::class, 'add'])->name('add_by_api');
Route::get('downloads', [\App\Http\Controllers\DownloaderController::class, 'downloads']);
Route::get('downloads/{id}', [\App\Http\Controllers\DownloaderController::class, 'downloadByID']);
