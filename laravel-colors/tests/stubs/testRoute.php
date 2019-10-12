<?php

Route::get('/laravel-colors/colors', 'LaravelColorsController@index');

Route::post('/laravel-colors/save', 'LaravelColorsController@create');

Route::post('/laravel-colors/update', 'LaravelColorsController@update');

Route::post('/laravel-colors/delete', 'LaravelColorsController@delete');
