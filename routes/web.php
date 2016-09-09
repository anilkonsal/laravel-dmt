<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/',[
  'uses'  =>  'HomeController@index'
]);

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/report/total', [
    'as'    =>  'total_report',
    'uses'  =>  'ReportController@total'
]);

Route::get('/report/album', [
    'as'    =>  'album_report',
    'uses'  =>  'ReportController@album'
]);
