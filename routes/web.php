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

Route::get('/', 'HomeController@index')->name('home');

Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('login/callback', 'Auth\LoginController@handleProviderCallback')->name('login.callback');

Route::group(['middleware' => ['auth', 'staff']], function () {

    Route::get('content', 'ContentController@index')->name('content');
    Route::post('content/load', 'ContentController@load')->name('content.load');
    Route::post('content/create', 'ContentController@create')->name('content.create');
    Route::post('content/update', 'ContentController@update')->name('content.update');
    Route::post('content/publish', 'ContentController@publish')->name('content.publish');

});
