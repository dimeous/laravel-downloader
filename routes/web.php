<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * Web Routes
 */
Route::get('/', 'HomeController')->name('home');
Route::post('prepare', 'DownloaderController@prepare')->name('prepare');
Route::get('status', 'DownloaderController@status')->name('status');

