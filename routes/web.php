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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('logs/' . env('CLAVE_UNICA_TOKEN_FALSO','94355a1824e3492298ccfbc7c96f04e4'), '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
