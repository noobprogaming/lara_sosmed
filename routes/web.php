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

Route::get('/', function() {
    return view('welcome');
});

Route::get('mhs','MhsController@index');
Route::get('mhs/tambah','MhsController@tambah');
Route::get('mhs/edit/{id}','MhsController@edit');
Route::get('mhs/delete/{id}','MhsController@delete');
Route::get('mhs/cari/','MhsController@cari');
Route::post('mhs/store','MhsController@store');
Route::put('mhs/edit/update/{id}','MhsController@update');

Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('/profile', 'ProfileController@index');
Route::get('/content', 'ContentController@index');
Route::get('/message','MessageController@index');

Route::post('store_content','HomeController@store_content');
Route::post('store_file','HomeController@store_file');
Route::post('store_comment', 'ContentController@store_comment');
Route::post('store_love', 'ContentController@store_love');
Route::post('store_message','MessageController@store_message');

Route::post('/profile/store_content','ProfileController@store_content');
Route::post('/profile/store_follow','ProfileController@store_follow');
Route::get('home/delete/{content_id}','HomeController@delete_content');

Route::get('real','MessageController@real');

Route::get('logout','LoginController@logout');