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

/**
 * SIP routes start here
 */
Route::group(['prefix'  =>  'sip', 'middleware' =>  'auth'], function(){
    Route::get('/generate-standalone', [
        'as'    =>  'sip_generate_standalone',
        'uses'  =>  'SipController@generateStandAlone'
    ]);
    Route::post('/generate-standalone', [
        'as'    =>  'sip_generate_standalone',
        'uses'  =>  'SipController@generateStandAlone'
    ]);
    Route::get('/generate-album', [
        'as'    =>  'sip_generate_album',
        'uses'  =>  'SipController@generateAlbum'
    ]);
    Route::post('/generate-album', [
        'as'    =>  'sip_generate_album',
        'uses'  =>  'SipController@generateAlbum'
    ]);
    Route::get('/generate-standalone-pdf', [
        'as'    =>  'sip_generate_standalone_pdf',
        'uses'  =>  'SipController@generateStandAlonePdf'
    ]);
    Route::post('/generate-standalone-pdf', [
        'as'    =>  'sip_generate_standalone_pdf',
        'uses'  =>  'SipController@generateStandAlonePdf'
    ]);
});


/**
 * Report Routes start here
 */
Route::group(['prefix'  =>  'report', 'middleware'  =>  'auth'], function(){
    Route::get('/total-album-counts', [
        'as'    =>  'total_album_counts_report',
        'uses'  =>  'ReportController@totalAlbumCounts'
    ]);
    Route::get('/total', [
        'as'    =>  'total_report',
        'uses'  =>  'ReportController@total'
    ]);
    Route::get('/album', [
        'as'    =>  'album_report',
        'uses'  =>  'ReportController@album'
    ]);
    Route::get('/standalone', [
        'as'    =>  'standalone_report',
        'uses'  =>  'ReportController@standalone'
    ]);
    Route::get('/album-images-not-migrated', [
        'as'    =>  'album_images_not_migrated_report',
        'uses'  =>  'ReportController@getAlbumImagesNotMigratedCounts'
    ]);
    Route::get('/standalone-images-not-migrated', [
        'as'    =>  'standalone_images_not_migrated_report',
        'uses'  =>  'ReportController@getStandaloneImagesNotMigratedCounts'
    ]);
    Route::get('/details', [
        'as'    =>  'details_report',
        'uses'  =>  'ReportController@details'
    ]);
    Route::post('/details', [
        'as'    =>  'details_report',
        'uses'  =>  'ReportController@details'
    ]);
    Route::get('/acms-albums-migration', [
        'as'    =>  'acms_albums_migration_report',
        'uses'  =>  'ReportController@acmsAlbumsMigration'
    ]);
    Route::get('/millenium-albums-migration', [
        'as'    =>  'millenium_albums_migration_report',
        'uses'  =>  'ReportController@milleniumAlbumsMigration'
    ]);
    Route::get('/qa', [
        'as'    =>  'get_ingest_qa',
        'uses'  =>  'ReportController@getIngestQa'
    ]);
    Route::post('/qa', [
        'as'    =>  'post_ingest_qa',
        'uses'  =>  'ReportController@postIngestQa'
    ]);
});
