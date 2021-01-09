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
Route::get('clients/{client}','ClientController@client'); // fetch single client
Route::resource('clients', 'ClientController')->except(['show','edit']);

//Route::get('transactions/{client}','TransactionController@index')->name('transaction.index'); // fetch single client

Route::resource('transactions', 'TransactionController')->except(['show','edit']);



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
