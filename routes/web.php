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
    'middleware' => 'auth',
    'uses'  =>  'ReportController@total'
]);

Route::get('/report/album', [
    'as'    =>  'album_report',
    'middleware' => 'auth',
    'uses'  =>  'ReportController@album'
]);

Route::get('/report/standalone', [
    'as'    =>  'standalone_report',
    'middleware' => 'auth',
    'uses'  =>  'ReportController@standalone'
]);

Route::get('/report/album-images-not-migrated', [
    'as'    =>  'album_images_not_migrated_report',
    'middleware' => 'auth',
    'uses'  =>  'ReportController@getAlbumImagesNotMigratedCounts'
]);
Route::get('/report/details', [
    'as'    =>  'details_report',
    'middleware' => 'auth',
    'uses'  =>  'ReportController@details'
]);
Route::post('/report/details', [
    'as'    =>  'details_report',
    'middleware' => 'auth',
    'uses'  =>  'ReportController@details'
]);
