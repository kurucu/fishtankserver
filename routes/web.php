<?php

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

Route::get('/', 'FishtankController@index');

Route::get('/state/{state?}', 'FishtankController@set_state');

Route::get('/get_state', 'FishtankController@get_state');
