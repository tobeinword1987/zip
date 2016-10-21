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

//Route::get('/', function () {
//    return view('zip');
//});

Route::get('/', 'ZipController@firstPage');

Route::get('/getTemplatesOfGenerator','ZipController@getTemplatesOfGenerator');

Route::get('/getPromoFiles','ZipController@getPromoFiles');

Route::post('/','ZipController@uploadZip');
