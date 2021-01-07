<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/**
 * Client CRUD API
 */
Route::get('clients/{client}','ClientController@client');
Route::get('clients','ClientController@index');
Route::post('clients/store/','ClientController@store');
Route::patch('clients/update/{client}','ClientController@update');
Route::delete('clients/destroy/{client}','ClientController@destroy');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
