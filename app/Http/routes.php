<?php

Route::get('/', 'AppController@index');

Route::get('/search', 'SearchController@index');

Route::get('/movie/{title}', 'MovieController@index');

Route::get('/graph', 'GraphController@index');