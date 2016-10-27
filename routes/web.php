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

Route::get('/', 'ZipController@firstPage');

Route::post('/','ZipController@uploadZip');

Route::get('/uploadTemplates', 'TemplateController@uploadTemplates');

Route::post('/uploadTemplates', 'TemplateController@addReplaceTemplates');

Route::get('/editTemplates', 'TemplateController@editTemplates');
