<?php


Route::get('/', function () {
    return view('ligne')->with('cool', "ligne");
});

Route::get('lignes', 'LigneController@index');

Route::get('/ligne/{id}/direction/{direction}', 'LigneController@show')->name("show");


