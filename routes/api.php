<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('clients','ClientController@index');
Route::post('clients/store/','ClientController@store');
Route::patch('clients/update/{client}','ClientController@update');
Route::delete('clients/destroy/{client}','ClientController@store');
//Route::resource('clients', 'ClientController');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
