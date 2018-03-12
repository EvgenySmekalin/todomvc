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

Route::get('/', 'ListController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/list', 'ListController@list');

Route::post('/list', 'ListController@create');
Route::post('/edit/{id}/list', 'ListController@create');

Route::delete('/list', 'ListController@delete');
Route::delete('/edit/{id}/list', 'ListController@delete');

Route::patch('/list', 'ListController@patch');
Route::patch('/edit/{id}/list', 'ListController@patch');

Route::get('/edit/{id}/{filter}', 'HomeController@userList');
