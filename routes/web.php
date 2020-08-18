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
//Route::post('/comment/reply', 'HomeController@reply')->name('comment_reply');
Route::get('/comment/delete', 'HomeController@delete')->name('comment_delete');



Route::get('/users', 'HomeController@users')->name('users');

Route::match(['get', 'post'], '/lib/{id}', 'LibController@index')->name('lib');

Route::get('/book/{id}', 'LibController@book')->name('book');


Route::post('/lib/form/create', 'LibController@create')->name('book_create');
Route::post('/book/{id}/edit', 'LibController@edit')->name('book_edit');
Route::post('/lib/form/open', 'LibController@open')->name('book_open');
Route::get('/lib/form/delete', 'LibController@delete')->name('book_delete');
