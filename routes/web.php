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

Auth::routes();

Route::get('todomvc/{filter?}', 'ListController@index');
Route::get('/todomvc', 'ListController@index')->name('/');

Route::get('/', function () {
    return redirect()->route('/');
});

Route::get('todomvc/list', 'ListController@list');
Route::post('todomvc/list', 'ListController@create');
Route::delete('todomvc/list', 'ListController@delete');
Route::patch('todomvc/list', 'ListController@patch');
Route::post('todomvc/toggle-all', 'ListController@toggleAll');
Route::post('todomvc/clear-completed', 'ListController@clearCompleted');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/change-name', 'HomeController@changeName');
    Route::get('/delete-list', 'HomeController@deleteList');
    Route::get('/add-list',     'HomeController@addList');

    Route::patch('/edit/{listId}/list',    'ListController@patch');
    Route::delete('/edit/{listId}/list',   'ListController@delete');
    Route::post('/edit/{listId}/list',     'ListController@create');
    Route::post('/edit/{listId}/toggle-all',      'ListController@toggleAll');
    Route::post('/edit/{listId}/clear-completed', 'ListController@clearCompleted');


    Route::get('/edit/{id}/{filter}', 'HomeController@userList');

    Route::get('/home', 'HomeController@index')->name('home');
});