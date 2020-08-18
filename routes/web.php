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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::match(['get', 'post'], '/profile/{id}', 'HomeController@index')->name('profile');

Route::get('/getcomment', 'HomeController@get_comments');

Route::get('/comments/{id}', 'HomeController@comments')->name('comments');


Route::post('/comment/create', 'HomeController@create')->name('comment_create');
Route::get('/comment/delete', 'HomeController@delete')->name('comment_delete');



Route::get('/users', 'HomeController@users')->name('users');

Route::match(['get', 'post'], '/lib/{id}', 'LibController@index')->name('lib');
Route::post('/lib/form/share', 'LibController@share')->name('lib_share');
Route::post('/lib/form/take', 'LibController@take_access')->name('lib_take');


Route::get('/book/{id}', 'BookController@index')->name('book');

Route::post('/book/form/create', 'BookController@create')->name('book_create');
Route::post('/book/{id}/edit', 'BookController@edit')->name('book_edit');
Route::post('/book/form/open', 'BookController@open')->name('book_open');
Route::get('/book/form/delete', 'BookController@delete')->name('book_delete');
