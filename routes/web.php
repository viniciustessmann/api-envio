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

Route::get('/', 'ImportsController@get');

Route::get('/import-state', 'StatesController@import');
Route::get('/import-state-relationship', 'StatesController@importRelationship');
Route::get('/import', 'ImportsController@import');

Route::get('/get-codes', 'StatesController@getCodes');
Route::get('/get-state', 'StatesController@get');
Route::get('/calculate', 'ShipmentsController@calculateForm');
Route::match(['get', 'post'], '/calculate-request', 'ShipmentsController@calculateRequest')->name('calculate-request');
